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
class PagesController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Pages';

        public $components = array('PagSeguro.Carrinho', 'PagSeguro.Notificacao', 'PagSeguro.Consulta');
        
        
        protected function _setCredenciais($email, $token, $component = 'Carrinho')
        {
            if ( $this->Session->read('email') && $this->Session->read('token') ) {
                    $this->{$component}->setCredenciais($this->Session->read('email'), 
                        $this->Session->read('token'));
                } else {
                    $this->{$component}->setCredenciais($this->request->data['Consulta']['email'], 
                        $this->request->data['Consulta']['token']);
                }
        }
        
        public function consulta()
        {
            if ( $this->request->is('post') ) {
                
                if ($this->request->data['Consulta']['tipo'] == 'transaction') {
                    
                    $this->_setCredenciais($this->request->data['Consulta']['email'], $this->request->data['Consulta']['token']);
                    
                    $transacaoId = $this->request->data['Consulta']['codigo'];

                    if ($this->Carrinho->obterInformacoesTransacao($transacaoId)) {

                        $dadosUsuario = $this->Carrinho->obterDadosUsuario();
                        $statusTransacao = $this->Carrinho->obterStatusTransacao();
                        $dadosPagamento = $this->Carrinho->obterDadosPagamento();
                        $dataTransacao = $this->Carrinho->obterDataTransacao();
                        $valores = $this->Carrinho->obterValores();
                        
                    }
                } else {
                    
                    $this->_setCredenciais($this->request->data['Consulta']['email'], $this->request->data['Consulta']['token'], 'Notificacao');

                    if ( $this->Notificacao->obterDadosTransacao('transaction', $this->request->data['Consulta']['codigo']) ) {
                        
                        $dadosUsuario = $this->Notificacao->obterDadosUsuario();
                        $statusTransacao = $this->Notificacao->obterStatusTransacao();
                        $dadosPagamento = $this->Notificacao->obterDadosPagamento();
                        $dataTransacao = $this->Notificacao->obterDataTransacao();
                        $valores = $this->Notificacao->obterValores();
                        
                    }
                }
                
                if ( $this->request->data['Consulta']['armazenar'] == 'on' ) {
                    $this->Session->write('email', $this->request->data['Consulta']['email']);
                    $this->Session->write('token', $this->request->data['Consulta']['token']);
                } else {
                    $this->Session->delete('email');
                    $this->Session->delete('token');
                }
                
                $this->set(compact('dadosUsuario', 'statusTransacao', 'dadosPagamento', 'dataTransacao', 'valores'));
                
                $this->view = 'resultado';
            }
        }
}
