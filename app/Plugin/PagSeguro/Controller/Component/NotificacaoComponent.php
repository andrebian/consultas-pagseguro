<?php
/**
* Plugin de integração com a API de notificações do PagSeguro e CakePHP.
*
* Versão do PHP 5.*
*
*
* @author Andre Cardoso <andrecardosodev@gmail.com>
* @link https://github.com/andrebian/cake-plugin-pagseguro/
* @authorURI http://andrebian.com
* @license MIT (http://opensource.org/licenses/MIT)
* @version 1.4
* @since 1.1
* 
* ESTE PLUGIN UTILIZA A API DO PAGSEGURO, DISPONÍVEL EM  (https://pagseguro.uol.com.br/v2/guia-de-integracao/tutorial-da-biblioteca-pagseguro-em-php.html)
* 
*/

App::uses('PagSeguroLibrary', 'Plugin/PagSeguro/Vendor/PagSeguroLibrary');
App::uses('Codes', 'Plugin/PagSeguro/Vendor');

class NotificacaoComponent extends Component{
    
    private $loadPagSeguroLibrary = null;
    private $credenciais = null;
    private $dadosTransacao = null;
    
    
 /**
  * 
  * @param \Controller $controller
  * @since 1.0
  */   
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
  * Define as credenciais para utilização do PagSeguro
  * 
  * @param string $email
  * @param string $token
  * @since 1.0
  */   
    public function setCredenciais($email, $token) {
        $this->credenciais = new PagSeguroAccountCredentials($email, $token);
    }
    
    
 /**
  * Inicia a consulta através de um código de notificação recebido
  * 
  * @param string $tipo
  * @param string $codigo
  * @return boolean
  * @since 1.0
  */   
    public function obterDadosTransacao($tipo, $codigo) {
                $tipoNotificacao = new PagSeguroNotificationType($tipo);
    		$strTipo = $tipoNotificacao->getTypeFromValue();
                
                try{
                    if ( $strTipo == 'TRANSACTION' ) {
                        if ( $this->__transactionNotification($codigo) ) {
                            return true;
                        }
                    }
                } catch (PagSeguroServiceException $e) {
                    echo $e->getMessage();
                    exit();
                }
    }
    
    
 /**
  * Retorna os dados do comprador
  * 
  * @return array
  * @since 1.0
  */   
    public function obterDadosUsuario() {
        $contato = $this->dadosTransacao->getSender();
        $endereco = $this->dadosTransacao->getShipping()->getAddress();
        $dadosUsuario = 
            array(  'nome' => $contato->getName(),
                    'email' => $contato->getEmail(),
                    'telefoneCompleto' => $contato->getPhone()->getAreaCode().' '.$contato->getPhone()->getNumber(),
                    'codigoArea' => $contato->getPhone()->getAreaCode(),
                    'numeroTelefone' => $contato->getPhone()->getNumber(),
                    'endereco' => $endereco->getStreet(),
                    'numero' => $endereco->getNumber(),
                    'complemento' => $endereco->getComplement(),
                    'bairro' => $endereco->getDistrict(),
                    'cidade' => $endereco->getCity(),
                    'cep' => $endereco->getPostalCode(),
                    'uf' => $endereco->getState(),
                    'pais' => $endereco->getCountry()
             ) ;
        return $dadosUsuario;
    }
    
    
    /**
  * Retorna em modo de array o status da transação pesquisada
  * 
  * @return array
  * @since 1.0
  */   
    public function obterStatusTransacao() {
        return Codes::obterStatusTransacao($this->dadosTransacao->getStatus()->getValue());
    }
    
    
 /**
  * Retorna tipo e meio de pagamento
  * 
  * @return array
  * @since 1.0
  */   
    public function obterDadosPagamento() {
        $dadosPagamento = array(
            'tipo' => Codes::obterTipoPagamento($this->dadosTransacao->getPaymentMethod()->getType()->getValue()),
            'metodo' => Codes::obterMeioPagamento($this->dadosTransacao->getPaymentMethod()->getCode()->getValue())
        );
        
        return $dadosPagamento;
    }
    
    
 /**
  * Retorna um array contendo a data em forma iso para o banco de dados
  * e em ptBR para exibição somente
  * 
  * @return array
  * @since 1.0
  */   
    public function obterDataTransacao() {
        $data['iso'] = $this->dadosTransacao->getDate();
        $data['ptBr'] = date('d/m/Y H:i:s', strtotime($data['iso']));
        $data['ultimaTentativaIso'] = $this->dadosTransacao->getLastEventDate();
        $data['ultimaTentativaPtBr'] = date('d/m/Y H:i:s', strtotime($data['ultimaTentativaIso']));
        
        return $data;
        
    }
    
    
 /**
  * obtervalores method
  * 
  * @return array
  * @since 1.1
  */   
    public function obterValores() {
        foreach($this->dadosTransacao->getItems() as $item) {
            $itens[] = array(   'id' => $item->getId(), 
                                'descricao' => $item->getDescription() , 
                                'quantidade' => $item->getQuantity(),
                                'valorUnitario' => $item->getAmount(),
                                'peso' => $item->getDescription(),
                                'frete' => $item->getShippingCost()
                            );
        }
        
        $dados = array(
            'referencia' => $this->dadosTransacao->getReference(),
            'valorTotal' => $this->dadosTransacao->getGrossAmount(),
            'descontoAplicado' => $this->dadosTransacao->getDiscountAmount(),
            'valorExtra' => $this->dadosTransacao->getExtraAmount(),
            'produtos' => $itens,
        );
        
        return $dados;
    }
    
    
   /**
   * 
   * @return string
   * @since 1.5
   */
    public function obterCodigoTransacao() {
        return $this->dadosTransacao->getcode();
    }
    
    
    /**
  * 
  * @param string $notificationCode
  * @since 1.0
  */   
    private function __transactionNotification($notificationCode) {	
    	try {
            if ( $this->dadosTransacao = $transaction = PagSeguroNotificationService::checkTransaction($this->credenciais, $notificationCode) ) {
                return true;
            }
    	} catch (PagSeguroServiceException $e) {
            echo $e->getMessage();
            exit();
    	}
    	
    }
    
}

?>
