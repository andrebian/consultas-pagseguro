<?php 
echo $this->Form->create('Consulta');
echo $this->Form->input('email', array('label' => 'Email (o email da conta em que a requisição é realizada)', 'value' => @$this->Session->read('email')));
echo $this->Form->input('token', array('label' => 'Token (o token da conta em que a requisição é realizada)', 'value' => @$this->Session->read('token')));
echo $this->Form->input('tipo', array('type' => 'select', 'options' => array('transaction' => 'Id Transação', 
    'notification' => 'Notificação'), 'label' => 'Tipo (Id Transação: Retorno do PagSeguro | Notificação: Posts enviados de tempo em tempo pelo PagSeguro)'));
echo $this->Form->input('codigo', array('label' => 'Código'));
$check = false;
if ( $this->Session->read('email') && $this->Session->read('token') ) {
    $check = true;
}
echo $this->Form->input('armazenar', array('type' => 'checkbox', 'label' => 'Armazenar email e token em sessão para outras consultas', 'checked' => $check));
echo $this->Form->end('Consultar');