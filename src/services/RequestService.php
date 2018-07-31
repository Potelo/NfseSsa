<?php

namespace Potelo\NfseSsa\Services;


use Potelo\NfseSsa\MySoapClient;
use Potelo\NfseSsa\Request\Error;
use Potelo\NfseSsa\Request\Response;

class RequestService
{

    /**
     * @var string
     */
    public $certificatePrivate;

    /**
     * @var string
     */
    public $certificatePrivatePassword;

    /**
     * @var string
     */
    private $urlBase;

    /**
     * @var array
     */
    private $soapOptions;


    public function __construct()
    {
        $this->urlBase = 'https://notahml.salvador.ba.gov.br';

        $this->certificatePrivate = config('nfse-ssa.certificado_privado_path');

        $this->certificatePrivatePassword = config('nfse-ssa.certificado_privado_senha');

        $context = stream_context_create([
            'ssl' => [
                // set some SSL/TLS specific options
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);

        $this->soapOptions = [
            'keep_alive' => true,
            'trace' => true,
            'local_cert' => $this->certificatePrivate,
            'passphrase' => $this->certificatePrivatePassword,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'stream_context' => $context
        ];

    }

    /**
     * @param $wsdlSuffix
     * @param $xml
     * @param $method
     * @param $return
     * @return Response
     */
    private function consult($wsdlSuffix, $xml, $method, $return){
        $wsdl = $this->urlBase . $wsdlSuffix;

        $client = new MySoapClient($wsdl, $this->soapOptions);

        $params = new \SoapVar($xml, XSD_ANYXML);

        $result = call_user_func_array([$client, $method], [$params]);

        $xmlObj = simplexml_load_string($result->{$return});

        $response = new Response();

        if (isset($xmlObj->ListaMensagemRetorno)) {
            $response->setStatus(false);

            foreach ($xmlObj->ListaMensagemRetorno->MensagemRetorno as $mensagem) {
                $error = new Error();

                $arr = get_object_vars($mensagem);

                $error->codigo = $arr['Codigo'];
                $error->mensagem = $arr['Mensagem'];
                $error->correcao = $arr['Correcao'];
                $response->addError($error);
            }

        } else {
            $response->setStatus(true);

            $data = [];
            foreach (get_object_vars($xmlObj) as $key => $value) {
                $data[snake_case($key)] = $value;
            }

            $response->setData($data);
        }

        return $response;
    }

    /**
     * @param $xml
     * @param $mainTagName
     * @return string
     */
    private function generateXmlBody($xml, $mainTagName)
    {
        return "
            <$mainTagName xmlns='http://tempuri.org/'>
                <loteXML>
                  <![CDATA[$xml]]>
                </loteXML>
            </$mainTagName>
        ";
    }


    /**
     * @param $xml
     * @return Response
     */
    public function enviarLoteRps($xml)
    {
        $wsdlSuffix = '/rps/ENVIOLOTERPS/EnvioLoteRPS.svc?wsdl';

        $finalXml = $this->generateXmlBody($xml, 'EnviarLoteRPS');

        $response = $this->consult(
            $wsdlSuffix,
            $finalXml,
            'EnviarLoteRPS',
            'EnviarLoteRPSResult');

        return $response;
    }

    /**
     * @param $xml
     * @return Response
     */
    public function consultarSituacaoLoteRps($xml)
    {
        $wsdlSuffix = '/rps/CONSULTASITUACAOLOTERPS/ConsultaSituacaoLoteRPS.svc?wsdl';

        $finalXml = $this->generateXmlBody($xml, 'ConsultarSituacaoLoteRPS');

        $response = $this->consult(
            $wsdlSuffix,
            $finalXml,
            'ConsultarSituacaoLoteRPS',
            'ConsultarSituacaoLoteRPSResult');

        return $response;
    }
}