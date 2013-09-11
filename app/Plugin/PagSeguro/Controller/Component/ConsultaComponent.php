<?php
/**
* Plugin de integração com a API do PagSeguro e CakePHP (API de verdade, não somente montar a string e enviar por post).
*
* Versão do PHP 5.*
*
*
* @author Andre Cardoso <andrecardosodev@gmail.com>
* @link https://github.com/andrebian/cake-plugin-pagseguro/
* @authorURI http://andrebian.com
* @license MIT (http://opensource.org/licenses/MIT)
* @version 1.4
* @since 1.3 
* 
* ESTE PLUGIN UTILIZA A API DO PAGSEGURO, DISPONÍVEL EM  https://pagseguro.uol.com.br/v2/guia-de-integracao/tutorial-da-biblioteca-pagseguro-em-php.html
* 
* 
* 
* PLUGIN BASE (enviando dados via POST):
*   https://github.com/ftgoncalves/pagseguro/  de Felipe Theodoro Gonçalves, (http://ftgoncalves.com.br)
*/

App::uses('PagSeguroLibrary', 'Plugin/PagSeguro/Vendor/PagSeguroLibrary');
App::uses('Codes', 'Plugin/PagSeguro/Vendor');

class ConsultaComponent extends Component{
    private $loadPagSeguroLibrary = null;
    private $credenciais = null;
    private $transacoes = null;
    
    
    public function startup(\Controller $controller) {
        $this->loadPagSeguroLibrary = new PagSeguroLibrary;
        
        // definindo alguns dados padrões
        $config = Configure::read('PagSeguro');
        if ( $config ) {
            $this->credenciais = new PagSeguroAccountCredentials($config['credenciais']['email'], $config['credenciais']['token']);
        }
        
        parent::startup($controller);
    }
    
    
 /**
  * Define as credenciais do vendedor
  * 
  * @param string $email
  * @param string $token
  * @since 1.2
  */   
    public function setCredenciais($email, $token) {
        $this->credenciais = new PagSeguroAccountCredentials($email, $token);
    }
    
    
 /**
  * Inicia a consulta por transações no período solocitado
  * 
  * @param string $dataInicial (YYYY-mm-dd)
  * @param string $dataFinal (YYYY-mm-dd)
  * @param int $pagina
  * @param int $quantidadeMaximaRegistros
  * @return boolean
  */   
    public function obterTransacoesPorPeriodo($dataFinal, $pagina = 1, $quantidadeMaximaRegistros = 30) {
        $dataInicial = date('Y-m-d',  strtotime($dataFinal.'-1month '));
        $dataInicial .= 'T00:01';
        $dataFinal .= 'T23:59';
        
        try {
            if ( $result = $this->transacoes = PagSeguroTransactionSearchService::searchByDate($this->credenciais, $pagina, $quantidadeMaximaRegistros, $dataInicial, $dataFinal) ) {
                return $this->__montarDetalhesTransacoes($result);
            }
        } catch (PagSeguroServiceException $e) {
            echo $e->getMessage();
            exit();
        }
    }
    
    
/**
  * Inicia a consulta por transações abandonadas em um período de 1 mês
  * 
  * @param string $dataInicial (YYYY-mm-dd)
  * @param string $dataFinal (YYYY-mm-dd)
  * @param int $pagina
  * @param int $quantidadeMaximaRegistros
  * @return boolean
  * @since 1.4
  */   
    public function obterTransacoesAbandonadas($dataFinal, $pagina = 1, $quantidadeMaximaRegistros = 30) {
        $dataInicial = date('Y-m-d',  strtotime($dataFinal.'-1month '));
        $dataInicial .= 'T00:01';
        $dataFinal .= 'T23:59';
        
        try {
            if ( $result = $this->transacoes = PagSeguroTransactionSearchService::searchAbandoned($this->credenciais, $pagina, $quantidadeMaximaRegistros, $dataInicial, $dataFinal) ) {
                return $this->__montarDetalhesTransacoesAbandonadas($result);
            }
        } catch (PagSeguroServiceException $e) {
            echo $e->getMessage();
            exit();
        }
    }
    
    
    private function __montarDetalhesTransacoes(PagSeguroTransactionSearchResult $result){
        $dadosTransacao = array();
        foreach($result->getTransactions() as $transacoes) {
            $dadosTransacao[] = array(
                'idTransacao' => $transacoes->getCode(),
                'referencia' => $transacoes->getReference(),
                'valorTotal' => $transacoes->getGrossAmount(),
                'tipoTransacao' => Codes::obterTipoTransacao($transacoes->getType()->getValue()),
                'statusTransacao' => Codes::obterStatusTransacao($transacoes->getStatus()->getValue(),'notarray'),
                'desconto' => $transacoes->getDiscountAmount(),
                'valorExtra' => $transacoes->getExtraAmount(),
                'tipoPagamento' => Codes::obterTipoPagamento($transacoes->getPaymentMethod()->getType()->getValue()),
                'dataIso' => $transacoes->getDate(),
                'dataPtBR' => date('d/m/Y H:i:s', strtotime($transacoes->getDate())),
                'ultimaTentativaIso' => $transacoes->getLastEventDate(), 
                'ultimaTentativaPtBR' => date('d/m/Y H:i:s', strtotime($transacoes->getLastEventDate())),
                
            );
        }
        return $dadosTransacao;
    }
    
    
    private function __montarDetalhesTransacoesAbandonadas(PagSeguroTransactionSearchResult $result){
        $dadosTransacao = array();
        foreach($result->getTransactions() as $transacoes) {
            $dadosTransacao[] = array(
                'idTransacao' => $transacoes->getCode(),
                'referencia' => $transacoes->getReference(),
                'valorTotal' => $transacoes->getGrossAmount(),
                'desconto' => $transacoes->getDiscountAmount(),
                'valorExtra' => $transacoes->getExtraAmount(),
                'dataIso' => $transacoes->getDate(),
                'dataPtBR' => date('d/m/Y H:i:s', strtotime($transacoes->getDate())),
                'ultimaTentativaIso' => $transacoes->getLastEventDate(), 
                'ultimaTentativaPtBR' => date('d/m/Y H:i:s', strtotime($transacoes->getLastEventDate())),
                
            );
        }
        return $dadosTransacao;
    }
    
    
}

?>
