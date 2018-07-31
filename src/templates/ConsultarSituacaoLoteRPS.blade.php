<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<ConsultarSituacaoLoteRpsEnvio xmlns="http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd">
    <Prestador>

        {!! array_xml_get($dados['prestador'], 'cnpj') !!}

        {!! array_xml_get($dados['prestador'], 'inscricao_municipal') !!}

    </Prestador>

    {!! array_xml_get($dados, 'protocolo') !!}
</ConsultarSituacaoLoteRpsEnvio>