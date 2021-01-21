<?php

namespace Potelo\NfseSsa;

/**
 * Classe complementar
 * necessária para a comunicação SOAP
 * Remove algumas tags para adequar a comunicação
 * ao padrão Windows utilizado
 */
class MySoapClient extends \SoapClient
{

    public $soapRequest;

    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {

        $request = str_replace('xmlns:ns2="<anyXML>"', '', $request);
        $request = str_replace(':ns1', '', $request);
        $request = str_replace('ns1:', '', $request);
        $request = str_replace("\n", '', $request);
        $request = str_replace("\r", '', $request);
        $request = preg_replace('/(\>)\s*(\<)/m', '$1$2', $request);

        //$request = $this->sanitizeOutput($request);

        $this->soapRequest = $request;

        return (parent::__doRequest($request, $location, $action, $version));
    }

    function sanitizeOutput($buffer)
    {

        $search = array(
            '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
            '/[^\S ]+\</s',     // strip whitespaces before tags, except space
            '/(\s)+/s',         // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/' // Remove HTML comments
        );

        $replace = array(
            '>',
            '<',
            '\\1',
            ''
        );

        $buffer = preg_replace($search, $replace, $buffer);

        return $buffer;
    }
}
