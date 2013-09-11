<?php

App::uses('AppController', 'Controller');

class ComprasController extends AppController {

        
  /**
   * Processa os produtos e envia a requisição ao PagSeguro
   * 
   */      
        public function checkout() {
                // caso não tenha definido as credenciais no bootstrap descomente a linha abaixo
                // e defina seus dados
                //$this->Carrinho->setCredenciais('seu-email', 'seu-token');
                
            
                // opcionais
                //$this->Carrinho->setUrlRetorno('url-de-retorno');
                //$this->Carrinho->setReferencia(25);
                
                
                $this->Carrinho->adicionarItem(1, 'Produto Teste', '0.01', '1000', 1);
                $this->Carrinho->adicionarItem(2, 'Produto Teste 2', '12.40', '1000', 1);
                $this->Carrinho->adicionarItem(3, 'Produto Teste 3', '27.90', '1000', 1);
                $this->Carrinho->setContatosComprador('nome do comprador', 'email-do-comprador', '41', '99999999');
                $this->Carrinho->setEnderecoComprador('80000000', 'Rua Teste', '0000', 'Complemento', 'Bairro', 'Cidade', 'UF');
                
                
                $this->Carrinho->setTipoFrete('SEDEX');
                
                // opcional
                $this->Carrinho->setValorTotalFrete('0.01');
                
                
                $this->Carrinho->setTipoPagamento('BOLETO');
                
                
                if ($result = $this->Carrinho->finalizaCompra() ) {
                    $this->redirect($result);
                }
        }
        
        
 /**
  * Utilizado para retorno e consulta por código
  */       
        public function retorno() {
            $idTransacao = $this->params['url']['transaction_id'];
                
            if ($this->Carrinho->obterInformacoesTransacao($idTransacao) ) {
                $dadosUsuario = $this->Carrinho->obterDadosUsuario();
                debug($dadosUsuario);
                
                $statusTransacao = $this->Carrinho->obterStatusTransacao();
                debug($statusTransacao);
                
                $dadosPagamento = $this->Carrinho->obterDadosPagamento();
                debug($dadosPagamento);
                
                $dataTransacao = $this->Carrinho->obterDataTransacao();
                debug($dataTransacao);
                
                $valores = $this->Carrinho->obterValores();
                debug($valores);
            }
        }
        
        
 /**
  * Retorna as transações por período
  * Informe a data final que a inicial é automaticamente setada para 1 mês antes
  */       
        public function consultaPorPeriodo() {
            if ( $consulta = $this->Consulta->obterTransacoesPorPeriodo(date('Y-m-d')) ) {
                debug($consulta);
            }
        }
        
 
 /**
  * Retorna as transações abandonadas por período
  * Informe a data final que a inicial é automaticamente setada para 1 mês antes
  */        
        public function consultaTransacoesAbandonadas() {
            if ( $consulta = $this->Consulta->obterTransacoesAbandonadas(date('Y-m-d')) ) {
                debug($consulta);
            }
        }
    
}
