<?php

/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Pages';
    public $components = array('PagSeguro.PagSeguro', 'PagSeguro.RetornoPagSeguro', 'PagSeguro.Notificacao');

    protected function _setCredenciais($email, $token, $component = 'RetornoPagSeguro')
    {
        $sessionEmail = $this->Session->read('email');
        if(!empty($sessionEmail) &&   $email != $sessionEmail) {
            $this->Session->write('email', $email);
        }
        
        $tokenSession = $this->Session->read('token');
        if(!empty($tokenSession) &&  $token != $tokenSession) {
            $this->Session->write('token', $token);
        }
        
        if ($this->Session->read('email') && $this->Session->read('token')) {
            $this->{$component}->setCredenciais($this->Session->read('email'), $this->Session->read('token'));
        } else {
            $this->{$component}->setCredenciais($this->request->data['Consulta']['email'], $this->request->data['Consulta']['token']);
        }
    }

    public function consulta()
    {
        if ($this->request->is('post')) {

            if ($this->request->data['Consulta']['tipo'] == 'transaction') {

                $transacaoId = $this->request->data['Consulta']['codigo'];
                $this->_setCredenciais($this->request->data['Consulta']['email'], $this->request->data['Consulta']['token'], 'RetornoPagSeguro');

                if ($this->RetornoPagSeguro->obterInformacoesTransacao($transacaoId)) {

                    $dadosUsuario = $this->RetornoPagSeguro->obterDadosUsuario();
                    $statusTransacao = $this->RetornoPagSeguro->obterStatusTransacao();
                    $dadosPagamento = $this->RetornoPagSeguro->obterDadosPagamento();
                    $dataTransacao = $this->RetornoPagSeguro->obterDataTransacao();
                    $valores = $this->RetornoPagSeguro->obterValores();
                }
            } else {

                $this->_setCredenciais($this->request->data['Consulta']['email'], $this->request->data['Consulta']['token'], 'Notificacao');

                if ($this->Notificacao->obterDadosTransacao('transaction', $this->request->data['Consulta']['codigo'])) {

                    $dadosUsuario = $this->Notificacao->obterDadosUsuario();
                    $statusTransacao = $this->Notificacao->obterStatusTransacao();
                    $dadosPagamento = $this->Notificacao->obterDadosPagamento();
                    $dataTransacao = $this->Notificacao->obterDataTransacao();
                    $valores = $this->Notificacao->obterValores();
                }
            }
            
            

            if ($this->request->data['Consulta']['armazenar'] == 'on') {
                $this->Session->write('email', $this->request->data['Consulta']['email']);
                $this->Session->write('token', $this->request->data['Consulta']['token']);
            } else {
                $this->Session->delete('email');
                $this->Session->delete('token');
            }

            $this->set(compact('dadosUsuario', 'statusTransacao', 'dadosPagamento', 'dataTransacao', 'valores'));

            $this->view = 'resultado';
        }

        // Caso não exista nenhuma transação a ser consultada, descomente as linhas abaixo para gerar uma nova
//        $this->Checkout->defineReferencia(25);
//        $this->Checkout->adicionarItem(1, 'Produto Teste', '25.00', '1000', 1);
//        $this->Checkout->defineContatosComprador('Jon Snow', 'andre@redsuns.com.br', '41', '88888888');
//        $this->Checkout->defineEnderecoComprador('82900270', 'Rua Miguel Caluf', '1234', 'Complemento', 'Cajuru', 'Curitiba', 'PR');
//        $this->Checkout->defineTipoFrete(PagSeguroEntrega::TIPO_SEDEX);
//        $this->Checkout->defineValorTotalFrete('1.00');
//        $this->Checkout->defineTipoPagamento(
//                PagSeguroTiposPagamento::tipoDePagamentoEmString(
//                        PagSeguroTiposPagamento::TIPO_PAGAMENTO_BOLETO
//                )
//        );
//        if ($result = $this->Checkout->finalizaCompra()) {
//            var_dump($result);
//        }
    }

}
