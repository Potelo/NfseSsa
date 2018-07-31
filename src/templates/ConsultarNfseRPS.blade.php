<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<ConsultarNfseRpsEnvio xmlns="http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd">
    <IdentificacaoRps>
        {!! array_xml_get($dados['identificacao_rps'], 'numero') !!}

        {!! array_xml_get($dados['identificacao_rps'], 'serie') !!}

        {!! array_xml_get($dados['identificacao_rps'], 'tipo') !!}
    </IdentificacaoRps>

    <Prestador>
        {!! array_xml_get($dados['prestador'], 'cnpj') !!}

        {!! array_xml_get($dados['prestador'], 'inscricao_municipal') !!}
    </Prestador>
</ConsultarNfseRpsEnvio>