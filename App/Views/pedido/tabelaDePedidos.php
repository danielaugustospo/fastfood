<style>
        .hideTabela {
            display: none;
        }
    #id_situacao_pedido {
        padding: 0px !important;
        background: #faf8f3;
        border: 1px solid #dee2e6;
    }

    @media screen and (max-width: 600px) {
  table {
    border: 0;
  }

  table caption {
    font-size: 1.3em;
  }
  
  table thead {
    border: none;
    clip: rect(0 0 0 0);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute;
    width: 1px;
  }
  
  table tr {
    border-bottom: 3px solid #ddd;
    display: block;
    margin-bottom: .625em;
  }
  
  table td {
    border-bottom: 1px solid #ddd;
    display: block;
    font-size: .8em;
    text-align: right;
  }
  
  table td::before {
    /*
    * aria-label has no advantage, it won't be read inside a table
    content: attr(aria-label);
    */
    content: attr(data-label);
    float: left;
    font-weight: bold;
    text-transform: uppercase;
  }
  
  table td:last-child {
    border-bottom: 0;
  }
}

    /* @media only screen and (max-width: 600px) {
        th {
            font-size: 10px !important;
        }

        #id_situacao_pedido {
            width: 50px !important;
        } */


</style>
<?php $rota = BASEURL . '/pedido/modalFormulario';
require_once('cupomFiscal.php'); ?>



<div class="modal fade" id="modalMesas" tabindex="-1" role="dialog" aria-labelledby="modalMesas" aria-hidden="true">


    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Mesas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>

                </button>
            </div>

            <div class="modal-body">

                <div class="row col-sm-12">
                    <?php foreach ($mesas as $mesa) :
                        if ($mesa->situacao == '1000' || $mesa->situacao == null) {
                            $statusmesa = "Livre";
                            $fundodiv = "aqua";
                        } else {
                            $statusmesa = "Ocupada";
                            $fundodiv = "#8A3E44";
                        }
                    ?>
                        <div class="col-sm-4 d-flex justify-content-around">
                            <div class="card" onclick='modalFormularioPedido("<?php echo $rota; ?>", null, + "<?php echo $mesa->id; ?>");' style="width: 18rem; background-color:<?php echo $fundodiv ?>;">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $mesa->nomemesa; ?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $statusmesa; ?></h6>

                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <!-- <button type="button" class="btn btn-primary">Salvar Alterações</button> -->
            </div>
        </div>
    </div>
</div>
<?php if (count($pedidos) > 0) : ?>
    <table id="example" class="table tabela-ajustada table-striped" style="width:100%">
        <thead>
            <tr>
                <th style="width:-webkit-fill-available;" class="hidden-when-mobile">Nº</th>
                <th style="width:-webkit-fill-available;">Cliente</th>
                <th style="width:-webkit-fill-available;">Pedido</th>
                <th style="width:-webkit-fill-available;">Total</th>
                <th style="width:-webkit-fill-available;">Situação</th>
                <th style="width:-webkit-fill-available;" class="hidden-when-mobile">Mesa</th>
                <th style="width:-webkit-fill-available;">Endereço</th>
                <th style="width:-webkit-fill-available;">Celular</th>
                <th style="width:-webkit-fill-available;" class="hidden-when-mobile">Entrega</th>
                <th style="width:-webkit-fill-available;" class="hidden-when-mobile">Forma de Pagamento</th>
                <th style="width:-webkit-fill-available;" class="hideTabela">Id Meio Pagamento</th>
                <th style="width:-webkit-fill-available;" class="hideTabela">Id Vendedor</th>
                <th style="width:-webkit-fill-available;" class="hideTabela">Id Empresa</th>
                <th style="width:-webkit-fill-available;" class="hideTabela">Valor Desconto</th>
                <th style="width:-webkit-fill-available;" class="hideTabela">Valor Frete</th>
                <th style="width:-webkit-fill-available;" class="hideTabela">Id Produto</th>
                <th style="width:-webkit-fill-available;" class="hideTabela">Preço</th>
                <th style="width:-webkit-fill-available;" class="hideTabela">Qtd</th>
                <th style="width:-webkit-fill-available;" class="hideTabela">Subtotal</th>
                <th style="width:-webkit-fill-available; text-align:right;padding-right:0">

                    <button onclick="modalFormularioPedido('<?php echo $rota; ?>', null);" class="btn btn-sm btn-success" title="Delivery">
                        Delivery
                        <i class="fa fa-motorcycle" aria-hidden="true"></i>
                    </button>
                    <!-- <button type="button" class="btn btn-sm btn-success" title="Mesas" data-toggle="modal" data-target="#modalMesas">
                        Consumo no local (Mesas)
                    </button> -->
                    <button type="button" class="btn btn-sm btn-secondary" onclick="alert('Recurso ainda não disponível');" title="Mesas" data-toggle="modal" data-target="#">
                        Consumo no local (Mesas)
                    </button>
                </th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($pedidos as $pedido) : ?>
                <tr>
                    <td data-label="N°" data-idpedido="<?php echo $pedido->idPedido; ?>" class="hidden-when-mobile"><?php echo $pedido->idPedido; ?></td>
                    <td data-label="Cliente" data-nomecliente="<?php echo $pedido->nomeCliente; ?>"><?php echo $pedido->nomeCliente; ?></td>
                    <td data-label="Pedido" data-nomeproduto="<?php echo $pedido->nomeproduto; ?>">
                        <?php echo $pedido->nomeproduto; ?>
                    </td>
                    <td data-label="Total" data-totalgeral="<?php echo real($pedido->totalGeral); ?>">R$ <?php echo real($pedido->totalGeral); ?></td>
                    <td data-label="Situação">
                    <?php if(($pedido->id_situacao_pedido != '1000') && ($pedido->id_situacao_pedido != '2000')): ?>
                        
                        <select name="id_situacao_pedido" id="id_situacao_pedido" onchange="alterarSituacaoPedido(<?php echo $pedido->idPedido; ?>, $(this).val(), <?php echo $pedido->mesa; ?>)">
                            <?php foreach ($situacoesPedidos as $situacaoPedido) : ?>
                                <?php

                                if (($situacaoPedido->id == $pedido->id_situacao_pedido) && ($situacaoPedido->legenda != 'Finalizado')) { ?>
                                    <option value="<?php echo $situacaoPedido->id; ?>" selected="selected">
                                        <?php echo $situacaoPedido->legenda; ?>
                                    </option>
                                <?php } elseif ($situacaoPedido->legenda != 'Finalizado') { ?>
                                    <option value="<?php echo $situacaoPedido->id; ?>">
                                        <?php echo $situacaoPedido->legenda; ?>
                                    </option>
                                <?php }  ?>
                            <?php endforeach; ?>
                        </select>

                    <?php 
                        elseif ($pedido->id_situacao_pedido == '1000') : echo "Finalizado"; 
                        elseif ($pedido->id_situacao_pedido == '2000') : echo "Cancelado"; 
                        endif;
                    ?>    
                    </td>
                    <td data-label="Mesa" data-mesa="<?php echo $pedido->mesa; ?>" class="hidden-when-mobile">
                        <?php echo $pedido->mesa; ?>
                    </td>
                    <td data-label="Endereço" data-endereco="<?php echo $pedido->endereco; ?>">
                        <?php echo $pedido->endereco; ?>
                    </td>
                    <td data-label="Celular" data-celular="<?php echo $pedido->celular; ?>">
                        <?php echo $pedido->celular; ?>
                    </td>
                    <td data-label="Entrega" data-previsaoentrega="<?php echo ($pedido->previsaoEntrega == 'Não informado') ? '<small>' . $pedido->previsaoEntrega . '</small>' : $pedido->previsaoEntrega; ?>" class="hidden-when-mobile">
                        <?php echo ($pedido->previsaoEntrega == 'Não informado') ? '<small>' . $pedido->previsaoEntrega . '</small>' : $pedido->previsaoEntrega; ?>
                    </td>
                    <td data-label="Forma de Pagamento" data-formapagamento="<?php echo $pedido->forma_pagamento; ?>" class="hidden-when-mobile">
                        <?php echo $pedido->forma_pagamento; ?>
                    </td>
                    <td data-label="Id Meio de Pagamento" data-idmeiopagamento="<?php echo $pedido->id_meio_pagamento; ?>" class="hideTabela">
                        <?php echo $pedido->id_meio_pagamento; ?>
                    </td>
                    <td data-idvendedor="<?php echo $pedido->id_vendedor; ?>" class="hideTabela">
                        <?php echo $pedido->id_vendedor; ?>
                    </td>
                    <td data-idempresa="<?php echo $pedido->id_empresa; ?>" class="hideTabela">
                        <?php echo $pedido->id_empresa; ?>
                    </td>
                    <td data-valordesconto="<?php echo $pedido->valor_desconto; ?>" class="hideTabela">
                        <?php echo $pedido->valor_desconto; ?>
                    </td>
                    <td data-valorfrete="<?php echo $pedido->valor_frete; ?>" class="hideTabela">
                        <?php echo $pedido->valor_frete; ?>
                    </td>
                    <td data-idproduto="<?php echo $pedido->id_produto; ?>" class="hideTabela">
                        <?php echo $pedido->id_produto; ?>
                    </td>

                    <td data-preco="<?php echo $pedido->preco; ?>" class="hideTabela">
                        <?php echo $pedido->preco; ?>
                    </td>
                    <td data-quantidade="<?php echo $pedido->quantidade; ?>" class="hideTabela">
                        <?php echo $pedido->quantidade; ?>
                    </td>
                    <td data-subtotal="<?php echo $pedido->subtotal; ?>" class="hideTabela">
                        <?php echo $pedido->subtotal; ?>
                    </td>
                    <td style="text-align:right">

                    <?php if(($pedido->id_situacao_pedido != '1000') && ($pedido->id_situacao_pedido != '2000')):  //Finalizado ou Cancelado?> 
                        <input type="button" class="fechaVenda btn btn-sm btn-success" value="Fechar Venda">
                        <input type="button" class="cancelaVenda btn btn-sm btn-danger" value="Cancelar Venda"><br>
                    <?php elseif($pedido->id_situacao_pedido == '1000'): ?>
                        <input type="button" class="fechaVenda btn btn-sm btn-success" value="Venda Finalizada" disabled>
                    <?php elseif($pedido->id_situacao_pedido == '2000'): ?>
                        <input type="button" class="cancelaVenda btn btn-sm btn-danger" value="Venda Cancelada" disabled>
                    <?php endif;?>
                        <!-- <input type="button" class="clicador btn btn-sm btn-secondary" value="Visualizar" id="botaoModal" data-toggle="modal" data-target="#exampleModalCenter"> -->

                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cogs"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 23px, 0px); top: 0px; left: 0px; will-change: transform;">

                                <!-- <button class="dropdown-item" href="#" onclick="modalFormularioPedido('<?php echo $rota; ?>', <?php echo $pedido->idPedido; ?>, null)">
                                    <i class="fas fa-edit"></i> Editar
                                </button> -->
                                <button class="clicador dropdown-item" id="botaoModal" data-toggle="modal" data-target="#exampleModalCenter">
                                    <i class="fas fa-eye"></i> Visualizar
                                </button>

                                <!--<a class="dropdown-item" href="#">
                              <i class="fas fa-trash-alt" style="color:#cc6666"></i> Excluir
                            </a>-->

                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <tfoot></tfoot>
    </table>
<?php else : ?>
    <br><br><br>
    <center>
        <i class="fas fa-sad-tear" style="font-size:40px;opacity:0.70"></i>
        <br><br>
        <h6 style="opacity:0.70">Pedidos não encontrados!</h6>
        <button onclick="modalFormularioPedido('<?php echo $rota; ?>', null);" class="btn btn-sm btn-success" title="Cadastrar Pedido!">
            <i class="fas fa-plus"></i>
            Deseja cadastrar algum?
        </button>
    </center>
<?php endif; ?>