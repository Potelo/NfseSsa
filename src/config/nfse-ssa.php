<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Configurações
     |--------------------------------------------------------------------------
     |
     | Por padrão o pacote tenta utilizar o servidor de homologação
     |
     */

    'homologacao' => env('NFSESSA_HOMOLOGACAO', true),

    'certificado_privado_path' => null,

    'certificado_publico_path' => null,

];
