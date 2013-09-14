<ul class="maintabmenu">
    <li>
        <?php echo $this->Html->link('Nova Consulta', '/'); ?>
    </li>
    <li class="current">
        <a href="#">Detalhes da consulta</a>
    </li>
</ul><!--maintabmenu-->

<div class="content">
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Cliente</span></h2>
    </div><!--contenttitle-->
    <table cellpadding="0" cellspacing="0" border="0" class="stdtable">
                    <thead>
                        <tr>
                            <th class="head0">Campo</th>
                            <th class="head1">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Nome</td>
                            <td><?php echo $dadosUsuario['nome']; ?></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><?php echo $dadosUsuario['email']; ?></td>
                        </tr>
                        <tr>
                            <td>Telefone</td>
                            <td><?php echo $dadosUsuario['telefoneCompleto']; ?></td>
                        </tr>
                        <tr>
                            <td>Endereço</td>
                            <td><?php echo $dadosUsuario['endereco']; ?></td>
                        </tr>
                        <tr>
                            <td>Número</td>
                            <td><?php echo $dadosUsuario['numero']; ?></td>
                        </tr>
                        <tr>
                            <td>Complemento</td>
                            <td><?php echo $dadosUsuario['complemento']; ?></td>
                        </tr>
                        <tr>
                            <td>Bairro</td>
                            <td><?php echo $dadosUsuario['bairro']; ?></td>
                        </tr>
                        <tr>
                            <td>Cidade</td>
                            <td><?php echo $dadosUsuario['cidade']; ?></td>
                        </tr>
                        <tr>
                            <td>CEP</td>
                            <td><?php echo $dadosUsuario['cep']; ?></td>
                        </tr>
                    </tbody>
                </table>
                <br />
                <div class="notification msginfo">
                    <p>Status: <b><?php echo $statusTransacao; ?></b></p>
                </div>
                
                <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Pagamento</span></h2>
    </div><!--contenttitle-->
    <table cellpadding="0" cellspacing="0" border="0" class="stdtable">
                    <thead>
                        <tr>
                            <th class="head0">Tipo</th>
                            <th class="head1">Método</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $dadosPagamento['tipo']; ?></td>
                            <td><?php echo $dadosPagamento['metodo']; ?></td>
                        </tr>
                    </tbody>
    </table>
    <br />
    <div class="notification msginfo">
                    <p>Última Notificação: <b><?php echo date('d/m/Y H:i:s', strtotime($dataTransacao['ultimaTentativaIso'])); ?></b></p>
                </div>
    
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Valores e informações</span></h2>
    </div><!--contenttitle-->
    <table cellpadding="0" cellspacing="0" border="0" class="stdtable">
                    <thead>
                        <tr>
                            <th class="head0">Campo</th>
                            <th class="head1">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Referência</td>
                            <td><?php echo $valores['referencia']; ?></td>
                        </tr>
                        <tr>
                            <td>Desconto aplicado</td>
                            <td>R$ <?php echo number_format($valores['descontoAplicado'], 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Valor extra (adicional)</td>
                            <td>R$ <?php echo number_format($valores['valorExtra'], 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Valor final (total)</td>
                            <td>R$ <?php echo number_format($valores['valorTotal'], 2, ',', '.'); ?></td>
                        </tr>
                    </tbody>
    </table>
    <br />
    
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Produtos adquiridos</span></h2>
    </div><!--contenttitle-->
    <table cellpadding="0" cellspacing="0" border="0" class="stdtable">
                    <thead>
                        <tr>
                            <th class="head0">Id</th>
                            <th class="head1">Descrição</th>
                            <th class="head0">Quant</th>
                            <th class="head1">Valor Unit.</th>
                            <th class="head0">Peso</th>
                            <th class="head1">Frete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ( !empty($valores['produtos']) ) : ?>
                            <?php foreach( $valores['produtos'] as $produto ) : ?>
                                <tr>
                                    <td><?php echo $produto['id']; ?></td>
                                    <td><?php echo $produto['descricao']; ?></td>
                                    <td><?php echo $produto['quantidade']; ?></td>
                                    <td>R$ <?php echo number_format($produto['valorUnitario'], 2, ',', '.'); ?></td>
                                    <td><?php echo $produto['peso']; ?></td>
                                    <td>R$ <?php echo number_format($produto['frete'], 2, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
    </table>
</div>