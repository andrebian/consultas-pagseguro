# PAGSEGURO PLUGIN
_v 1.5_


Facilita a integração de pagamentos via PagSeguro em aplicações desenvolvidas com base no CakePHP 2.x.
O plugin realiza apenas interfaceamento para a API de pagamentos do PagSeguro, com
isso nem o plugin nem o PagSeguro podem ser responsabilizados por uso em desconformidade
à documentação fornecida pelo PagSeguro <https://pagseguro.uol.com.br/v2/guia-de-integracao/visao-geral.html> 
assim como valores fornecidos. A responsabilidade das corretas informações ao PagSeguro são
estritamente do programador que criará a requisição no fechamento do carrinho de compras.



## INSTALAÇÃO
=============


Zip

    Baixe o plugin, descompacte-o na pasta `app/Plugin`, renomeie a pasta `cake-plugin-pagseguro` para `PagSeguro`

Git

    Submodulo
        Na raiz de sua aplicação adicione como submodulo: 
       `git submodule add git@github.com:andrebian/cake-plugin-pagseguro.git app/Plugin/PagSeguro`
        

    Clonando  
        `git clone git@github.com:andrebian/cake-plugin-pagseguro.git`
        Altere o nome da pasta de `cake-plugin-pagseguro` para `PagSeguro` e cole-a na pasta `Plugin` de sua aplicação


Composer

    `{
        "require": {
            "andrebian/pag_seguro": "dev-master",
        }
    }`

## CONFIGURAÇÕES
================

### Carregando o plugin

No arquivo `bootstrap.php` adicione o suporte ao plugin:
`CakePlugin::load('PagSeguro');` ou `CakePlugin::loadAll();`


### Credenciais

Você deve possuir uma conta no PagSeguro pois precisará setar as credenciais,
estas credenciais são compostas pelo seu email e o token que deve ser configurado na seção de integração
junto ao PagSeguro.

Tal configuração pode ser feita de duas formas, via `bootstrap` ou no controller desejado.

Arquivo bootstrap
`<?php
	    ...
	    Configure::write('PagSeguro.credenciais', array(
		  'email' => 'seu email',
		  'token' => 'seu token'
	    )); `

Controller qualquer onde será montada a finalização da compra
` <?php
	    $this->Carrinho->setCredenciais('seu email', 'seu token'); `


A configuração das credenciais podem ser definidas no `bootstrap` e alteradas caso necessário em qualquer controller


### Carregando o componente


Agora que você já configurou suas credenciais deve definir no `AppController` ou no controller
que o componente será utilizado

```php public $components = array('PagSeguro.Carrinho'); ```


caso já possua mais componentes faça-o da seguinte forma
```php public $components = array('Outros componentes.....','PagSeguro.Carrinho');```



## UTILIZAÇÃO
=============

* Requisição de pagamento: Leia o arquivo REQUISICAO_PAGAMENTO
* Retorno de requisição de pagamento: Leia o arquivo CONSULTA
* Consulta por código: Leia o arquivo CONSULTA
* Consulta por perído: Leia o arquivo CONSULTA
* Consulta por transações abandonadas: Leia o arquivo CONSULTA
* Notificações: Leia o arquivo NOTIFICACAO

====================


TODO

* Testes
