# CLASSE CONSULTA

No controller desejado deve ser declarada a utilização do componente `Consulta`

`public $components = array('demais componentes', 'PagSeguro.Consulta');`

## Consultar transações por código

Esta ação é o ideal para tratar o retorno do pagseguro via GET. Atribuindo 
o código em uma variável agora você pode consultar o status da transação.
No controller que receberá o retorno do PagSeguro você deve primeiramente definir 
suas credenciais e com o código de retorno chamar o método `obterInformacoesTransacao` do 
componente `Carrinho`, feito isto basta buscar pelas informações desejadas, sendo elas:
* Dados do usuário;
* Status da transação;
* Dados de pagamento;
* Data (de origem e última notificação do PagSeguro)
* Dados dos produtos comprados

As informações acima são idênticas para a API de notificação


```php
            // recebendo o id da transação via GET (URL)
            $idTransacao = $this->params['url']['transaction_id'];
            
            // definindo credenciais caso não tenham sido definidas no bootstrap
            $this->Carrinho->setCredenciais('seu email', 'seu token');
            
            // caso haja dados a exibir...
            if ($this->Carrinho->obterInformacoesTransacao($idTransacao) ) {

                    // retorna os dados do usuário
                    $dadosUsuario = $this->Carrinho->obterDadosUsuario();
                    
                    // retorna o status da transação
                    $statusTransacao = $this->Carrinho->obterStatusTransacao();
                    
                    // retorna os dados de pagamento    
                    $dadosPagamento = $this->Carrinho->obterDadosPagamento();
                    
                    // retorna a data de compra e última interação
                    $dataTransacao = $this->Carrinho->obterDataTransacao();

                    // retorna detalhes dos produtos e valores
                    $produtos = $this->Carrinho->obterValores();


                    // agora exibindo todos os resultados

                    debug($dadosUsuario);
                    /*
                    array(
                        'nome' => 'Andre Cardoso',
                        'email' => 'andrecardosodev@gmail.com',
                        'telefoneCompleto' => '41 00000000',
                        'codigoArea' => '41',
                        'numeroTelefone' => '00000000',
                        'endereco' => 'Rua Teste',
                        'numero' => '1234',
                        'complemento' => 'Complemento teste',
                        'bairro' => 'Centro',
                        'cidade' => 'Curitiba',
                        'cep' => '80000000',
                        'uf' => 'PR',
                        'pais' => 'BRA'
                    )
                    */


                    debug($statusTransacao);
                    /*
                    array(
                        'id' => (int) 7,
                        'descricao' => 'Cancelada'
                    )
                    */


                    debug($dadosPagamento);
                    /*
                    array(
                        'tipo' => 'Boleto',
                        'metodo' => 'Boleto Santander'
                    )
                    */


                    debug($dataTransacao);
                    /*
                    array(
                        'iso' => '2012-11-24T13:14:41.000-02:00',
                        'ptBr' => '24/11/2012 13:14:41',
                        'ultimaTentativaIso' => '2012-12-08T07:34:15.000-02:00',
                        'ultimaTentativaPtBr' => '08/12/2012 07:34:15'
                    )    
                    */

                    
                    debug($produtos);
                    /*
                    array(
                        'valorTotal' => '0.01',
                        'descontoAplicado' => '0.00',
                        'valorExtra' => '0.00',
                        'produtos' => array(
                                (int) 0 => array(
                                        'id' => '1',
                                        'descricao' => 'Produto Teste',
                                        'quantidade' => '1',
                                        'valorUnitario' => '0.01',
                                        'peso' => '1000',
                                        'frete' => null
                                )
                        )
                    )
                    */
                    
                } 
```



## Consulta por período

O PagSeguro fornece a possibilidade de realizar uma consulta por período trazendo 
inúmeras transações e seus detalhes. Há apenas uma regra: O espaço entre a data
de início e fim da consulta deve possuir no máximo 30 dias. Por este motivo o plugin
solicita somente a data final, tratando automaticamente para 1 mês a menos a data
de início da consulta. Ex: para a data de fim 23/02/2013 a data de início será setada 
automaticamente como 22/01/2013.

```php
    /**
    *   obterTransacoesPorPeriodo
    *
    *   @param string $dataFim (formato YYYY-mm-dd)
    */

    if ( $consulta = $this->Consulta->obterTransacoesPorPeriodo('2013-02-22') ) {
        debug($consulta);
        /*
        array(
            (int) 0 => array(
                    'idTransacao' => '2C0C055B-2B10-42BE-A582-221135F8DAA7',
                    'referencia' => '25',
                    'valorTotal' => '0.01',
                    'tipoTransacao' => 'Pagamento',
                    'statusTransacao' => 'Aguardando pagamento',
                    'desconto' => '0.00',
                    'valorExtra' => '0.00',
                    'tipoPagamento' => 'Boleto',
                    'dataIso' => '2013-02-16T19:35:53.000-02:00',
                    'dataPtBR' => '16/02/2013 19:35:53',
                    'ultimaTentativaIso' => '2013-02-16T19:36:00.000-02:00',
                    'ultimaTentativaPtBR' => '16/02/2013 19:36:00'
            ),
        )

        */
    }
```

## Consultar por transações abandonadas
Esta ação serve para consultar todas as transações com status abandonado de um 
período de tempo de 1 mês. Basta informar a data de fim que o início dar-se-a automaticamente
1 mês a menos que a data fornecida.

```php
    if ( $consulta = $this->Consulta->obterTransacoesAbandonadas('2013-02-26') ) {
        debug($consulta);

        /*
        array(
            (int) 0 => array(
                    'idTransacao' => '4C0EFE17-FB2C-4186-BE93-2D8F381E0F6D',
                    'referencia' => '25',
                    'valorTotal' => '0.02',
                    'desconto' => null,
                    'valorExtra' => null,
                    'dataIso' => '2013-02-23T11:02:17.000-03:00',
                    'dataPtBR' => '23/02/2013 11:02:17',
                    'ultimaTentativaIso' => null,
                    'ultimaTentativaPtBR' => '31/12/1969 21:00:00'
            ),
            (int) 1 => array(
                    'idTransacao' => 'BDD76AD4-D886-4EE2-82B0-6EBBC7857252',
                    'referencia' => '25',
                    'valorTotal' => '97.30',
                    'desconto' => null,
                    'valorExtra' => null,
                    'dataIso' => '2013-02-23T11:00:47.000-03:00',
                    'dataPtBR' => '23/02/2013 11:00:47',
                    'ultimaTentativaIso' => null,
                    'ultimaTentativaPtBR' => '31/12/1969 21:00:00'
            ),
            ...
        ) 
        */
    }
```


### NOTA

A consulta por período não retorna listagem dos produtos comprados, para isto 
deve ser utilizado o código (idTransacao) e realizar a consulta por código.


