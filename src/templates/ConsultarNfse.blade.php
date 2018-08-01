<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<ConsultarNfseEnvio xmlns="http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd">
    <Prestador>
        {!! array_xml_get($dados['prestador'], 'cnpj') !!}

        {!! array_xml_get($dados['prestador'], 'inscricao_municipal') !!}
    </Prestador>

    {!! array_xml_get($dados, 'numero_nfse') !!}

    <PeriodoEmissao>
        {!! array_xml_get($dados['periodo_emissao'], 'data_inicial') !!}

        {!! array_xml_get($dados['periodo_emissao'], 'data_final') !!}
    </PeriodoEmissao>

    @if(isset($dados['tomador']))
    <Tomador>
        <IdentificacaoTomador>
            <CpfCnpj>
                {!! array_xml_get($dados['tomador']['cpf_cnpj'], 'cnpj') !!}

                {!! array_xml_get($dados['tomador']['cpf_cnpj'], 'cpf') !!}
            </CpfCnpj>
        </IdentificacaoTomador>

        {!! array_xml_get($dados['tomador'], 'inscricao_municipal') !!}
    </Tomador>
    @endif

</ConsultarNfseEnvio>