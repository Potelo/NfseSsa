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