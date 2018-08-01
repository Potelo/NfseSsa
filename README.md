# NfseSsa

## Introdução

NfseSsa é um pacote para laravel que fornece uma interface para emissão de 
Nota Fiscal de Serviços Eletrônica (NFS-e) em Salvador - BA.

## Instalação Laravel 5.x

Instale esse pacote pelo composer:

```
composer require potelo/nfse-ssa
```

Se você não utiliza o [auto-discovery](https://medium.com/@taylorotwell/package-auto-discovery-in-laravel-5-5-ea9e3ab20518), Adicione o ServiceProvider em config/app.php

```php
Potelo\NfseSsa\NfseSsaServiceProvider::class,
```

## Geração dos arquivos do certificado
Você deve ter recebido um certificado com a extensão **pfx**, ele serve para o ambiente
de produção e homologação. Vamos precisar converter 
esse arquivo para a extensão **pem** e também extrair a chave pública. 
Para extrair as duas chaves vamos utilizar os comandos no terminal (cmd no Windows)
e inserir a senha quando solicitado:
```
openssl pkcs12 -in Certificado.pfx -out priv.pem -nodes
```

```
openssl pkcs12 -in Certificado.pfx -clcerts -nokeys -out public.pem
```

Você deve guardar os dois arquivos gerados, **priv.pem** e **public.pem**.

 ## Configuração

Copie o arquivo de configuração do pacote para seu ambiente local, usando o comando publish:
```
php artisan vendor:publish --provider="Potelo\NfseSsa\NfseSsaServiceProvider"
```

Um arquivo **nfse-ssa.php** será criado na pasta **config**, você deve editar ele e colocar
os caminhos para os dois arquivos que foram gerados.
```
'homologacao' => env('NFSESSA_HOMOLOGACAO', true),

'certificado_privado_path' => storage_path('app/priv.pem'),

'certificado_publico_path' => storage_path('app/public.pem'),
```

No seu **env** adicione a variável:
```
NFSESSA_HOMOLOGACAO=true
```
Só mude para **false** quando for colocar em produção.

Quando tiver desenvolvendo, é essencial que utilize o painel web do ambiente de homologação
para liberar o cadastro e a emissão de notas fiscais:
https://notahml.salvador.ba.gov.br/

Utilize esse painel também para acompanhar se as notas fiscais estão sendo geradas corretamente.
O cadastro nesse painel precisa ser aprovado, entrando em contato por telefone com a prefeitura
é possível ativar o cadastro.

Perguntas e respostas:
https://nfse.sefaz.salvador.ba.gov.br/OnLine/Institucional/FaqTecnologia.aspx

## Emissão do Recibo Provisório de Serviços (RPS)

Para gerar a nota fiscal, precisamos enviar um RPS para a API da Prefeitura. 
Instanciamos o objeto NfseSsa por injeção de dependência no método do Controller e 
enviamos o RPS através do método **enviarLoteRps**:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Potelo\NfseSsa\NfseSsa;

class Controller extends BaseController{
  
    public function enviarRPS(NfseSsa $nfsa)
    {
        // ou $nfsa = app(NfseSsa::class);
        
        $result = $nfsa->enviarLoteRps([
          'numero_lote' => 1,
          'id' => '001',
          'cnpj' => '10880435000121',
          'inscricao_municipal' => '51559500163',
          'rps' => [
              'id' => 'rpsId001',
              'identificacao' => [
                  'numero' => 1,
                  'serie' => 'A',
                  'tipo' => 1 // 1 - RPS, 2 – Nota Fiscal Conjugada (Mista), 3 – Cupom 
              ],
              'data_emissao' => '2018-08-01T16:45:14',
              'natureza_operacao' => 1, 
              /* Código de natureza da operação
                  1 – Tributação no município
                  2 - Tributação fora do município
                  3 - Isenção
                  4 - Imune
                  5 –Exigibilidade suspensa por decisão judicial
                  6 – Exigibilidade  */
              'regime_especial_tributacao' => 1,
              /* Código de identificação do regime especial de
                 tributação
                 1 – Microempresa municipal
                 2 - Estimativa
                 3 – Sociedade de profissionais
                 4 – Cooperativa
                 5 - Microempresário Individual (MEI)
                 6 - Microempresário e Empresa de Pequeno Porte
                 (ME EPP) */
              'optante_simples_nacional' => 1,
              'incentivador_cultural' => 2,
              'status' => 1,
              'servico' => [
                  'valores' => [
                      'valor_servicos' => 340.26,
                      'valor_deducoes' => 0,
                      'valor_pis' => 0,
                      'valor_cofins' => 0,
                      'valor_ir' => 0,
                      'valor_csll' => 0,
                      'iss_retido' => 2,
                      'valor_iss' => 6.81,
                      'valor_iss_retido' => 6.81,
                      'outras_retencoes' => 0,
                      'base_calculo' => 340.26,
                      'aliquota' => 0.02,
                      'valor_liquido_nfse' => 3345.45,
                      'desconto_incondicionado' => 0,
                      'desconto_condicionado' => 0,
                  ],
                  'item_lista_servico' => 1001,
                  'codigo_cnae' => 6622300,
                  'discriminacao' => 'vendas de seguro',
                  'codigo_municipio' => 2927408,
              ],
              'prestador' => [
                  'cnpj' => '10880435000121',
                  'inscricao_municipal' => '51559500163',
              ],
              'tomador' => [
                  'identificacao_tomador' => [
                      'cpf_cnpj' => [
                          'cnpj' => '48109110000899',
                          // 'cpf' => null // OU CPF
                      ],
                      'inscricao_municipal' => '51559500163',
                  ],
                  'razao_social' => 'RAZAO SOCIAL DO CLIENTE S/A',
                  'endereco' => [
                      'endereco' => 'R MANOEL DIAS DA SILVA',
                      'numero' => '999',
                      'bairro' => 'PITUBA',
                      'codigo_municipio' => '2927408',
                      'uf' => 'BA',
                      'cep' => '41000000',
                  ],
                  'contato' => [
                      'telefone' => '71991891137',
                      'email' => 'gpensador2@gmai.com'
                  ]
              ]
          ]
        ]);
        
        // Sucesso
        if ($result->getStatus()) {
            return $result->getData();
        }
    
        return $result->getErrors();
    }
  
}
```