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


    public function __construct()
    {
        $this->urlBase = 'https://notahml.salvador.ba.gov.br';

        $this->certificatePrivate = config('nfse-ssa.certificado_privado_path');

        $this->certificatePrivatePassword = config('nfse-ssa.certificado_privado_senha');

    }


    /**
     * @param $xml
     * @return Response
     */
    public function enviarLoteRps($xml)
    {
        $wsdl = $this->urlBase . '/rps/ENVIOLOTERPS/EnvioLoteRPS.svc?wsdl';

        $context = stream_context_create([
            'ssl' => [
                // set some SSL/TLS specific options
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);

        $options = array(
            'keep_alive' => true,
            'trace' => true,
            'local_cert' => $this->certificatePrivate,
            'passphrase' => $this->certificatePrivatePassword,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'stream_context' => $context
        );


        $client = new MySoapClient($wsdl, $options);

        $finalXml = '
            <EnviarLoteRPS xmlns="http://tempuri.org/">
                <loteXML>
                  <![CDATA[' . $xml . ']]>
                </loteXML>
            </EnviarLoteRPS>
        ';

        $params = new \SoapVar($finalXml, XSD_ANYXML);

        $result = $client->EnviarLoteRPS($params);

        $xmlObj = simplexml_load_string($result->EnviarLoteRPSResult);

        $response = new Response();

        if (isset($xmlObj->ListaMensagemRetorno)) {
            $response->setStatus(false);

            foreach ($xmlObj->ListaMensagemRetorno->MensagemRetorno as $mensagem) {
                $error = new Error();

                $error->codigo = $mensagem->Codigo;
                $error->mensagem = $mensagem->Mensagem;
                $error->correcao = $mensagem->Correcao;
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
}