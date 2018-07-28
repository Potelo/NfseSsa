<?php

namespace Potelo\NfseSsa\Services;


use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;

class SignatureService
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
    public $certificatePublic;

    public function __construct()
    {
        $this->certificatePrivate = config('nfse-ssa.certificado_privado_path');

        $this->certificatePrivatePassword = config('nfse-ssa.certificado_privado_senha');

        $this->certificatePublic = config('nfse-ssa.certificado_publico_path');
    }

    /**
     * @param $xml
     * @param bool $signRoot
     * @param array $tags
     * @return string
     * @throws \Exception
     */
    public function signXml($xml, $signRoot=true, $tags=[])
    {
        // Load the XML to be signed
        $doc = new \DOMDocument();
        $doc->loadXML($xml);

        // Create a new Security object
        $objDSig = new XMLSecurityDSig();

        // Use the c14n exclusive canonicalization
        $objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);

        // Sign using SHA-256
        $objDSig->addReference(
            $doc,
            XMLSecurityDSig::SHA1,
            array(
                'http://www.w3.org/2000/09/xmldsig#enveloped-signature'
            ),
            [
                'force_uri' => true
            ]
        );

        // Create a new (private) Security key
        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type'=>'private'));

        // If key has a passphrase, set it using
        $objKey->passphrase = $this->certificatePrivatePassword;

        // Load the private key
        $objKey->loadKey($this->certificatePrivate, TRUE);

        // Sign the XML file
        $objDSig->sign($objKey);

        // Add the associated public key to the signature
        $objDSig->add509Cert(file_get_contents($this->certificatePublic));

        if ($signRoot) {
            // Append the signature to the XML
            $objDSig->appendSignature($doc->documentElement);
        }

        foreach ($tags as $tag) {
            $rpsElements = $doc->getElementsByTagName($tag);

            foreach ($rpsElements as $rpsElement) {
                $objDSig->appendSignature($rpsElement);
            }
        }

        // The signed XML
        return $doc->saveXML();


    }
}