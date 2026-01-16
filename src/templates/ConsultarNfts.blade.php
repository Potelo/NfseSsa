<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<PedidoConsultaNFTS xmlns="http://www.salvador.ba.gov.br/nfts">
    <Cabecalho Versao="1">
        <Remetente>
            <CPFCNPJ>
                {!! array_xml_get($dados['Cabecalho']['Remetente']['CPFCNPJ'], 'CNPJ') !!}
            </CPFCNPJ>
        </Remetente>
    </Cabecalho>

    <DetalheNFTS>
        <ChaveNFTS>
            {!! array_xml_get($dados['DetalheNFTS']['ChaveNFTS'], 'InscricaoMunicipal') !!}

            {!! array_xml_get($dados['DetalheNFTS']['ChaveNFTS'], 'NumeroNFTS') !!}

            {!! array_xml_get($dados['DetalheNFTS']['ChaveNFTS'], 'CodigoVerificacao') !!}
        </ChaveNFTS>
    </DetalheNFTS>
</PedidoConsultaNFTS>