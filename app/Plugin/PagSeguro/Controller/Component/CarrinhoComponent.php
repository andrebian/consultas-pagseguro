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
* @since 1.0 
* 
* ESTE PLUGIN UTILIZA A API DO PAGSEGURO, DISPONÍVEL EM  https://pagseguro.uol.com.br/v2/guia-de-integracao/tutorial-da-biblioteca-pagseguro-em-php.html
* 
* 
* 
* PLUGIN BASE (enviando dados via POST):
*   https://github.com/ftgoncalves/pagseguro/  de Felipe Theodoro Gonçalves, (http://ftgoncalves.com.br)
*/

App::uses('PagSeguroLibrary', 'Plugin/PagSeguro/Vendor/PagSeguroLibrary');

class CarrinhoComponent extends Component{
    
    private $loadPagSeguroLibrary = null;
    private $credenciais = null;
    private $montaPagamento = null;
    private $consultaPorCodigo = null;
    private $comprador = null;
    private $tipoPagamento = null;
    private $Controller = null;
    
   
/**
 * 
 * @param \Controller $Controller
 * @since 1.0
 */    
    public function startup(\Controller $Controller) {
        
        // Instanciando classes para gerar o pagamento
        $this->loadPagSeguroLibrary = new PagSeguroLibrary;
        $this->montaPagamento = new PagSeguroPaymentRequest;
        $this->comprador = new PagSeguroSender;
        $this->tipoPagamento = new PagSeguroPaymentMethodType;
        
        // definindo alguns dados padrões
        $config = Configure::read('PagSeguro');
        if ( $config ) {
            $this->credenciais = new PagSeguroAccountCredentials($config['credenciais']['email'], $config['credenciais']['token']);
        }
        
        $this->montaPagamento->setShippingType('3');
        $this->montaPagamento->setCurrency('BRL');
               
        //parent::startup($Controller);
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
  * Define uma URL para após a finalização da interação do usuário com o PagSeguro
  * 
  * @param string $urlRetorno
  * @since 1.0
  */   
    public function setUrlRetorno($urlRetorno = null) {
       $this->montaPagamento->setRedirectURL($urlRetorno); 
    }
    
    
 /**
  * Seta a referência para a compra
  * 
  * @param int $id
  * @since 1.0
  */
    public function setReferencia($id) {
        $this->montaPagamento->setReference($id);
    }
    
    
/**
 * Adiciona um único item de cada vez ao "carrinho de compras"
 * 
 * @param int $id
 * @param string $nomeProduto
 * @param float $valorUnit
 * @param int $peso
 * @param int $quantidade
 * @param float $frete
 * @since 1.0
 */
    public function adicionarItem($id, $nomeProduto, $valorUnit, $peso, $quantidade = 1, $frete = null) {
        $this->montaPagamento->addItem($id, $nomeProduto, $quantidade, $valorUnit, $peso, $frete);
    }
    
    
 /**
  * Define um valor extra, ponto flutuante com 2 casas decimais
  * Pode ser valor positivo para acréscimo ou negativo para desconto
  * 
  * @param string $valor (formato 0.00)
  * @since 1.0
  */   
    public function setValorExtra($valor) {
        $this->montaPagamento->setExtraAmount($valor);
    }
    
    
 /**
  * Define em segundos por quanto tempo a requisição será válida
  * 
  * @param int $validade
  * @since 1.0
  */   
    public function setValidadeRequisicao($validade) {
        $this->montaPagamento->setMaxAge($validade);
    }
    
    
 /**
  * Define a quantidade de vezes que a requisição será utilizada
  * Útil para produtos ou taxas não variáveis
  * 
  * @param int $quantidade
  * @since 1.0
  */   
    public function setQuantidadeUso($quantidade) {
        $this->montaPagamento->setMaxUses($quantidade);
    }
    
    
 /**
  * Define os dados de contato do comprador
  * 
  * @param string $nome
  * @param string $email
  * @param string $codigoArea
  * @param string $numeroTelefone
  * @since 1.0
  */   
    public function setContatosComprador($nome, $email, $codigoArea, $numeroTelefone) {
        $this->montaPagamento->setSender($nome, $email, $codigoArea, $numeroTelefone);
    }
    
    
 /**
  * Define o endereço do comprador
  * 
  * @param string $cep
  * @param string $rua
  * @param string $numero
  * @param string $complemento
  * @param string $bairro
  * @param string $cidade
  * @param string $uf
  * @param string $pais Padrão BRA
  * @since 1.0
  */   
    public function setEnderecoComprador($cep, $rua, $numero, $complemento, $bairro, $cidade, $uf, $pais = 'BRA') {
        $this->montaPagamento->setShippingAddress($cep, $rua, $numero, $complemento, $bairro, $cidade, $uf, $pais);
    }
    
    
 /**
  * Define o tipo de frete que será efetuado na compra
  * 
  * @param string $tipoFrete 
  * 1 	Encomenda normal (PAC).
  * 2 	SEDEX (SEDEX)
  * 3 	Tipo de frete não especificado (NAO_ESPECIFICADO ou outro qualquer) -> Padrão
  * @since 1.2
  * 
  */   
    public function setTipoFrete($tipoFrete) {
        
        switch ($tipoFrete) {
            case 'PAC':
                $tipoEntrega = '1';
                break;
            case 'SEDEX':
                $tipoEntrega = '2';
                break;
            default:
                $tipoEntrega = '3';
                break;
        }
        
        $this->montaPagamento->setShippingType($tipoEntrega);
    }
    
    
 /**
  * Define o valor total do frete na compra
  * 
  * @param string $valorTotalFrete
  * @since 1.2
  */   
    public function setValorTotalFrete($valorTotalFrete) {
        $this->montaPagamento->setShippingCost($valorTotalFrete);
    }
    
    
/**
 * Define o tipo de pagamento que será disponibilizado ao cliente.
 * Detalhe: O tipo de pagamento condiz somente com os tipos habilitados em sua conta
 * junto ao PagSeguro
 * 
 * @param string tipoPagamento (CREDIT_CARD, BOLETO, ONLINE_TRANSFER, BALANCE, OI_PAGGO)
 * @since 1.2
 * 
 */    
    public function setTipoPagamento($tipoPagamento) {
        $this->tipoPagamento->setByType($tipoPagamento);
    }    
    
    
 /**
  * E enfim enviando o usuário para o pagamento
  * 
  * @return boolean
  * @since 1.0
  */   
    public function finalizaCompra() {
        try {
            if ($url = $this->montaPagamento->register($this->credenciais) ) {
                return $url;
            }
        } catch (PagSeguroServiceException $e) {
            echo $e->getMessage();
            exit();
        }
    }
    
    
    ###############
    # MÓDULO PARA TRATAR DO RETORNO
    
    
 /**
  * Realiza a busca através do transaction id vindo do pagseguro via get ou post 
  * em sua URL de retorno
  * 
  * @param string $idTransacao
  * @return object
  * @since 1.0
  */    
    public function obterInformacoesTransacao($idTransacao) {
        try{
            if ($this->consultaPorCodigo = PagSeguroTransactionSearchService::searchByCode($this->credenciais, $idTransacao) ) {
                return true;
            }
        } catch (PagSeguroServiceException $e) {
            echo $e->getMessage();
            exit();
        }
    }
    
    
 /**
  * Retorna os dados do usuário após a consulta
  * 
  * @return array
  * @since 1.0
  */   
    public function obterDadosUsuario() {
        $contato = $this->consultaPorCodigo->getSender();
        $endereco = $this->consultaPorCodigo->getShipping()->getAddress();
        
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
        return Codes::obterStatusTransacao($this->consultaPorCodigo->getStatus()->getValue());
    }
    
    
 /**
  * Retorna tipo e meio de pagamento
  * 
  * @return array
  * @since 1.0
  */   
    public function obterDadosPagamento() {
        $dadosPagamento = array(
            'tipo' => Codes::obterTipoPagamento($this->consultaPorCodigo->getPaymentMethod()->getType()->getValue()),
            'metodo' => Codes::obterMeioPagamento($this->consultaPorCodigo->getPaymentMethod()->getCode()->getValue())
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
        $data['iso'] = $this->consultaPorCodigo->getDate();
        $data['ptBr'] = date('d/m/Y H:i:s', strtotime($data['iso']));
        $data['ultimaTentativaIso'] = $this->consultaPorCodigo->getLastEventDate();
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
        foreach($this->consultaPorCodigo->getItems() as $item) {
            $itens[] = array(   'id' => $item->getId(), 
                                'descricao' => $item->getDescription() , 
                                'quantidade' => $item->getQuantity(),
                                'valorUnitario' => $item->getAmount(),
                                'peso' => $item->getWeight(),
                                'frete' => $item->getShippingCost()
                            );
        }
        
        $dados = array(
            'referencia' => $this->consultaPorCodigo->getReference(),
            'valorTotal' => $this->consultaPorCodigo->getGrossAmount(),
            'descontoAplicado' => $this->consultaPorCodigo->getDiscountAmount(),
            'valorExtra' => $this->consultaPorCodigo->getExtraAmount(),
            'produtos' => $itens,
        );
        
        return $dados;
    }
    
    
}