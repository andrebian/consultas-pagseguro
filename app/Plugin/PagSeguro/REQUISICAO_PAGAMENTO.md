# REQUISIÇÃO DE PAGAMENTO

Para a realização de uma requisição simples, contendo somente os dados do comprador, 
meio de entrega não definido, não definido tipo de pagamento e valores adicionais siga o modelo abaixo.

No controller que fará o processamento dos itens comprados pelo usuário deverá se parecer com o exemplo abaixo:

```php
	<?php
	...

        // definindo suas credenciais
        $this->Carrinho->setCredenciais('seu email', 'seu token');

        // definindo a URL de retorno ao realizar o pagamento (opcional)
        $this->Carrinho->setUrlRetorno('http://andrebian.com');

        // definindo a referência da compra (opcional)
        $this->Carrinho->setReferencia(25);

        /**
        *   adicionarItem method
        *   @param int id  OBRIGATÓRIO
        *   @param string descricao OBRIGATÓRIO
        *   @param string valorUnitario (formato 0.00 -> ponto como separador 
        *       de centavos, e nada separando milhares) OBRIGATÓRIO
        *   @param string peso (formato em gramas ex: 1KG = 1000) OBRIGATÓRIO
        *   @param int quantidade OBRIGATÓRIO (padrão 1 unidade)
        *   @param string frete (valor do frete formato 0.00 -> ponto como separador
        *       de centavos e nada separando milhares) OPCIONAL
        */


        // para adicionar apenas 1 item:
        $this->Carrinho->adicionarItem(1, 'Produto Teste', '25.00', '1000', 1);


        // para adicionar vários itens
        // Opção 1:
        $this->Carrinho->adicionarItem(1, 'Produto Teste', '25.00', '1000', 1);
        $this->Carrinho->adicionarItem(2, 'Produto Teste 2', '12.40', '1000', 1);
        $this->Carrinho->adicionarItem(3, 'Produto Teste 3', '27.90', '1000', 1);
        
        // OPção 2:
        $cont = 1;
        foreach($itensSelecionados as $itens) {
            $this->Carrinho->adicionarItem($cont, $itens['nomeProduto'], $itens['precoProduto'], $itens['precoProduto'], $itens['quantidadeProduto']);
            $cont++;
        }

        // definindo o contato do comprador
        $this->Carrinho->setContatosComprador('Andre Cardoso', 'andrecardosodev@gmail.com', '41', '00000000');

        // definindo o endereço do comprador
        $this->Carrinho->setEnderecoComprador('00000000', 'Rua Teste', '1234', 'Complemento', 'Bairro', 'Cidade', 'UF');

        
        /**
        *   setTipoFrete method OPCIONAL
        *
        *   @param tipoFrete (PAC, SEDEX, NAO_ESPECIFICADO)
        *
        */
        $this->Carrinho->setTipoFrete('SEDEX');


        /**
        *   setValorFrete method OPCIONAL
        *
        *   @param string valorTotalFrete (formato 0.00 -> ponto como separador de
        *       centavos e nada separando milhares)
        *
        */
        $this->Carrinho->setValorTotalFrete('32.00');


        /**
        *   tipoPagamento method OPCIONAL
        *   
        *   @param string tipoPagamento (CREDIT_CARD, BOLETO, ONLINE_TRANSFER, BALANCE, OI_PAGGO)
        *
        */
        $this->Carrinho->setTipoPagamento('BOLETO');


        // e finalmente se os dados estivere corretos, redirecionando ao Pagseguro
        if ($result = $this->Carrinho->finalizaCompra() ) {
            $this->redirect($result);
        }
```
