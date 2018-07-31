<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<EnviarLoteRpsEnvio xmlns="http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd">
    <LoteRps id="{{ array_get($dados, 'id') }}">

        {!! array_xml_get($dados, 'numero_lote') !!}

        {!! array_xml_get($dados, 'cnpj') !!}

        {!! array_xml_get($dados, 'inscricao_municipal') !!}

        <QuantidadeRps>1</QuantidadeRps>
        <ListaRps>
            <Rps>
                <InfRps id="{{ array_get($dados['rps'], 'id') }}">
                    <IdentificacaoRps>
                        @foreach($dados['rps']['identificacao'] as $k => $identificacao)
                            {!! array_xml_get($dados['rps']['identificacao'], $k) !!}
                        @endforeach
                    </IdentificacaoRps>

                    {!! array_xml_get($dados['rps'], 'data_emissao') !!}

                    {!! array_xml_get($dados['rps'], 'natureza_operacao') !!}

                    {!! array_xml_get($dados['rps'], 'regime_especial_tributacao') !!}

                    {!! array_xml_get($dados['rps'], 'optante_simples_nacional') !!}

                    {!! array_xml_get($dados['rps'], 'incentivador_cultural') !!}

                    {!! array_xml_get($dados['rps'], 'status') !!}

                    <Servico>
                        <Valores>
                            @foreach($dados['rps']['servico']['valores'] as $k => $valor)
                                {!! array_xml_get($dados['rps']['servico']['valores'], $k) !!}
                            @endforeach
                        </Valores>

                        {!! array_xml_get($dados['rps']['servico'], 'item_lista_servico') !!}

                        {!! array_xml_get($dados['rps']['servico'], 'codigo_cnae') !!}

                        {!! array_xml_get($dados['rps']['servico'], 'discriminacao') !!}

                        {!! array_xml_get($dados['rps']['servico'], 'codigo_municipio') !!}

                    </Servico>
                    <Prestador>
                        {!! array_xml_get($dados['rps']['prestador'], 'cnpj') !!}

                        {!! array_xml_get($dados['rps']['prestador'], 'inscricao_municipal') !!}
                    </Prestador>
                    <Tomador>
                        <IdentificacaoTomador>
                            <CpfCnpj>
                                {!! array_xml_get($dados['rps']['tomador']['identificacao_tomador']['cpf_cnpj'], 'cnpj') !!}

                                {!! array_xml_get($dados['rps']['tomador']['identificacao_tomador']['cpf_cnpj'], 'cpf') !!}
                            </CpfCnpj>
                        </IdentificacaoTomador>

                        {!! array_xml_get($dados['rps']['tomador'], 'razao_social') !!}

                        <Endereco>
                            @foreach($dados['rps']['tomador']['endereco'] as $k => $valor)
                                {!! array_xml_get($dados['rps']['tomador']['endereco'], $k) !!}
                            @endforeach
                        </Endereco>
                    </Tomador>
                </InfRps>
            </Rps>
        </ListaRps>
    </LoteRps>
</EnviarLoteRpsEnvio>