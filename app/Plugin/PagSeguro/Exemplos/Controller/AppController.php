<?php

App::uses('Controller', 'Controller');

class AppController extends Controller {
    public $components = array('PagSeguro.Carrinho', 'PagSeguro.Notificacao', 'PagSeguro.Consulta');
}
