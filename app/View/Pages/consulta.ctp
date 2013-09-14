<ul class="maintabmenu">
    <li class="current">
        <?php echo $this->Html->link('Consulta', '/'); ?>
    </li>
</ul><!--maintabmenu-->

<div class="content">
    <?php echo $this->Form->create('Consulta', array('class' => 'stdform')); ?>
    <?php echo $this->Form->input('email', array('value' => @$this->Session->read('email'), 'div' => false)); ?>
    <b><?php echo $this->Html->link('?', '#', array('title' => 'O email da conta em que a requisição é realizada')); ?></b>
    <br /><br />
    <?php echo $this->Form->input('token', array('value' => @$this->Session->read('token'), 'div' => false)); ?>
    <b><?php echo $this->Html->link('?', '#', array('title' => 'O token da conta em que a requisição é realizada')); ?></b>
    <br /><br />
    <?php echo $this->Form->input('tipo', array('type' => 'select', 'options' => array('transaction' => 'Id Transação', 
    'notification' => 'Notificação'), 'label' => 'Tipo', 'div' => false)); ?>
    <b><?php echo $this->Html->link('?', '#', array('title' => 'Id Transação: Retorno do PagSeguro | Notificação: Posts enviados de tempo em tempo pelo PagSeguro')); ?></b>
    <br /><br />
    <?php echo $this->Form->input('codigo', array('label' => 'Código', 'div' => false)); ?>
    <b><?php echo $this->Html->link('?', '#', array('title' => 'Código da transação ou da Notificação')); ?></b>
    <br /><br />
    <?php
        $check = false;
        if ( $this->Session->read('email') && $this->Session->read('token') ) {
            $check = true;
        }
         ?>
    <input type="checkbox" name="data[Consulta][armazenar]" checked="<?php echo $check; ?>">
    Armazenar email e token em sessão para outras consultas</input>
    <br /><br /><br />
    <?php echo $this->Form->end('Consultar'); ?>
</div>