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

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();
        public $components = array('PagSeguro.Carrinho', 'PagSeguro.Notificacao', 'PagSeguro.Consulta');

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 */
	public function display() {
                
	}
        
        
        public function consulta()
        {
            if ( $this->request->is('post') ) {
                
                if ( $this->Session->read('email') && $this->Session->read('token') ) {
                    $this->Carrinho->setCredenciais($this->Session->read('email'), 
                        $this->Session->read('token'));
                } else {
                    $this->Carrinho->setCredenciais($this->request->data['Consulta']['email'], 
                        $this->request->data['Consulta']['token']);
                }
                
                if ($this->request->data['Consulta']['tipo'] == 'transaction') {
                    $transacaoId = $this->request->data['Consulta']['codigo'];

                    if ($this->Carrinho->obterInformacoesTransacao($transacaoId)) {

                        debug($this->Carrinho->obterDadosUsuario());
                        
                        debug($this->Carrinho->obterStatusTransacao());
                        
                        debug($this->Carrinho->obterDadosPagamento());
                        
                        debug($this->Carrinho->obterDataTransacao());
                        
                        debug($this->Carrinho->obterValores());
                    }
                } else {

                    if ( $this->Notificacao->obterDadosTransacao('transaction', $this->request->data['Consulta']['codigo']) ) {
                        debug($this->Notificacao->obterDadosUsuario());
                        
                        debug($this->Notificacao->obterStatusTransacao());
                        
                        debug($this->Notificacao->obterDadosPagamento());
                        
                        debug($this->Notificacao->obterDataTransacao());
                        
                        debug($this->Notificacao->obterValores());
                    }
                }
                
                if ( $this->request->data['Consulta']['armazenar'] == 1 ) {
                    $this->Session->write('email', $this->request->data['Consulta']['email']);
                    $this->Session->write('token', $this->request->data['Consulta']['token']);
                } else {
                    $this->Session->delete('email');
                    $this->Session->delete('token');
                }
            }
        }
}
