# API de notificação


Para utilizar o componente de notificação o mesmo deve ser declarado no `AppController` ou no controller que receberá a notificação.
`public $components = array('Demais componentes....', 'PagSeguro.Notificacao');`


O PagSeguro fornece a opção de configuração de uma URL para o recebimento de notificações.
Tal URL receberá uma requisição em formato POST contendo duas informações:
1 - Tipo da notificação; 2 - Código da notificação

Não se confunda, o código da transação e da notificação são diferentes para uma mesma compra, e a cada
notificação o código se altera.

Modelo recebido pelo Pagseguro:
```php
    POST http://lojamodelo.com.br/notificacao HTTP/1.1
    Host:pagseguro.uol.com.br
    Content-Length:85
    Content-Type:application/x-www-form-urlencoded
    notificationCode=766B9C-AD4B044B04DA-77742F5FA653-E1AB24
    notificationType=transaction 
```


Com tais dados em mãos você deve realizar a requisição das informações da transação.

No controller/action que receberá tal notificação basta realizar a chamada ao método `obterDadosTransacao` informando o tipo e código de notificação

```php
        $tipo = $this->request->data['notificationType'];
        $codigo = $this->request->data['notificationCode'];

        if ( $this->Notificacao->obterDadosTransacao($tipo, $codigo) ) {
            // retorna somente os dados do comprador
            $dadosUsuario = $this->Notificacao->obterDadosUsuario(); 

            // retorna o status da transação 
            $statusTransacao = $this->Notificacao->obterStatusTransacao();

            // retorna os dados de pagamento (tipo de pagamento e forma de pagamento)
            $dadosPagamento = $this->Notificacao->obterDadosPagamento();

            // retorna a data que a compra foi realizada e última notificação
            $dataTransacao = $this->Notificacao->obterDataTransacao();

            // retorna os dados de produtos comprados
            $produtos = $this->Notificacao->obterValores();

            
            // retorna o id da transação para consulta e/ou atualização
            $codigoTransacao = $this->Notificacao->obterCodigoTransacao();
            

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
                'complemento' => 'Complemento',
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
                'id' => (int) 1,
                'descricao' => 'Aguardando pagamento'
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
                'iso' => '2013-02-16T19:35:53.000-02:00',
                'ptBr' => '16/02/2013 19:35:53',
                'ultimaTentativaIso' => '2013-02-16T19:36:00.000-02:00',
                'ultimaTentativaPtBr' => '16/02/2013 19:36:00'
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


            debug($codigoTransacao);
            /*
                '39440128-1FF4-429A-A865-0001D1F18AED';
            */


        }
```
