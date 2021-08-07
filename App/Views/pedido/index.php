<!--Usando o Html Components-->
<?php

use System\HtmlComponents\Modal\Modal; ?>


<div class="row">

    <div class="card col-lg-12 content-div">
        <div class="card-body">
            <h5 class="card-title">
                <?php iconFilter(); ?>
                Filtros
            </h5>
        </div>

        <form method="POST" action="<?php echo BASEURL; ?>/pedido/tabelaDepedidosChamadosViaAjax" id="form">

            <!-- token de segurança -->
            <input type="hidden" name="_token" value="<?php echo TOKEN; ?>" />

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="periodo_de">Período de</label>
                        <input type="datetime-local" class="form-control" name="de" id="periodo_de" value="<?php echo $dates['start_of_month'] ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="periodo_ate">Período até</label>
                        <input type="datetime-local" class="form-control" name="ate" id="periodo_ate" value="<?php echo $dates['today'] ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tipo_data">Pesquisar pelo tipo</label>
                        <select name="data_tipo" id="data_tipo" class="form-control">
                            <option value="">Não filtrar por data</option>
                            <option value="pedido">Data de pedido</option>
                            <option value="entrega">Data de entrega</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="ativo">Situação</label>
                        <select name="situacao_pedido" id="situacao_pedido" class="form-control">
                            <option value="">Todas as situações</option>
                            <?php foreach ($situacoesPedidos as $situacaoPedido) : ?>
                                <option value="<?php echo $situacaoPedido->id; ?>">
                                    <?php echo $situacaoPedido->legenda; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="id_usuario">Clientes</label>
                        <select class="form-control" name="id_cliente" id="id_cliente_filtro">
                            <option value="todos">Todos</option>
                            <?php foreach ($clientes as $cliente) : ?>
                                <option value="<?php echo $cliente->id; ?>">
                                    <?php echo $cliente->nome; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-success text-right pull-right" id="buscar-pedidos" style="margin-left:10px">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </div>
            <!--end row-->
        </form>

        <br>

    </div>
</div>

<div class="row">
    <div class="card col-lg-12 content-div">
        <div class="card-body">
            <h5 class="card-title"><i class="fas fa-shopping-basket"></i> Pedidos</h5>

        </div>
        <div id="append-pedidos"></div>
        <br>
    </div>
</div>
<script src="<?php echo BASEURL; ?>/public/assets/js/core/jquery.min.js"></script>
<script src="<?php echo BASEURL; ?>/public/js/helpers.js"></script>

<?php Modal::start([
    'id' => 'modalPedidos',
    'width' => 'modal-lg',
    'title' => 'Cadastrar Pedido'
]); ?>

<div id="formulario"></div>

<?php Modal::stop(); ?>

<script>
    $(function() {
        $(document).on('click', '.fechaVenda', function(e) {
            e.preventDefault;
            var idPedido = $(this).closest('tr').find('td[data-idpedido]').data('idpedido');

            Swal.fire({
                title: 'Tem certeza que deseja fechar este Pedido?',
                text: "Ele será concluído como uma venda e esta operação não poderá ser desfeita!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, fechar Pedido!',
                cancelButtonText: 'Não, desejo voltar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Pedido Finalizado!',
                        'Este pedido foi finalizado e uma venda foi cadastrada no sistema.',
                        'success'
                    )

                    var idvendedor = $(this).closest('tr').find('td[data-idvendedor]').data('idvendedor');
                    var formapagamento = $(this).closest('tr').find('td[data-idmeiopagamento]').data('idmeiopagamento');
                    var idempresa = $(this).closest('tr').find('td[data-idempresa]').data('idempresa');
                    var idproduto = $(this).closest('tr').find('td[data-idproduto]').data('idproduto');
                    var preco = $(this).closest('tr').find('td[data-preco]').data('preco');

                    var quantidade = $(this).closest('tr').find('td[data-quantidade]').data('quantidade');
                    var subtotal = $(this).closest('tr').find('td[data-subtotal]').data('subtotal');

                    var rota = getDomain() + "/pedido/fecharPedido";

                    modalValidacao('Finalizando', 'Concluindo Venda...');

                    $.post(rota, {
                        '_token': '<?php echo TOKEN; ?>',
                        'id_usuario': idvendedor,
                        'id_meio_pagamento': formapagamento,
                        'id_empresa': idempresa,
                        'id_produto': idproduto,
                        'preco': preco,
                        'quantidade': quantidade,
                        'valor': subtotal,

                        'id_pedido': idPedido,
                    }, function(resultado) {
                        var retorno = JSON.stringify(resultado);
                        if (retorno.status == true) {
                            setTimeout(modalValidacaoClose, 800);
                            alert("Venda Finalizada com sucesso!")
                        } else {
                            setTimeout(modalValidacaoClose, 800);
                        }
                    })

                    pedidos();
                    return false;

                } else {
                    pedidos();
                    return false;
                }
            })

        });
    });
    $(function() {
        $(document).on('click', '.cancelaVenda', function(e) {
            e.preventDefault;
            var idPedido = $(this).closest('tr').find('td[data-idpedido]').data('idpedido');

            var idvendedor = $(this).closest('tr').find('td[data-idvendedor]').data('idvendedor');
            var idempresa = $(this).closest('tr').find('td[data-idempresa]').data('idempresa');
            var idproduto = $(this).closest('tr').find('td[data-idproduto]').data('idproduto');

            var rota = getDomain() + "/pedido/cancelarPedido";

            modalValidacao('Cancelando Pedido', 'Cancelando Pedido, aguarde...');

            $.post(rota, {
                '_token': '<?php echo TOKEN; ?>',
                'id_usuario': idvendedor,
                'id_empresa': idempresa,
                'id_produto': idproduto,

                'id_pedido': idPedido,
            }, function(resultado) {
                var retorno = JSON.stringify(resultado);
                if (retorno.status == true) {
                    setTimeout(modalValidacaoClose, 800);
                } else {
                    setTimeout(modalValidacaoClose, 800);
                }
            })

            pedidos();
            return false;
        });
    });


    // function modalValidacao(mensagem) {
    //     $(function() {
    //         $("#modal-validacao .modal-title").text("Validação");
    //         $("#modal-validacao #modal-body-content").html("<center><h3>" + mensagem + "</center></h3>");
    //         $("#modal-validacao").modal({backdrop: 'static'});
    //     });
    // }


    function modalFormularioPedido(rota, id, mesa) {
        var url = "";

        if (id) {
            url = rota + "/" + id;
        } else if (mesa) {
            url = rota + "/" + mesa;
        } else {
            url = rota;
        }

        $("#formulario").html("<center><h3>Carregando...</h3></center>");
        $("#modalPedidos").modal({
            backdrop: 'static'
        });
        $("#formulario").load(url);
    }


    function alterarSituacaoPedido(idPedido, idSituacaoPedido) {
        var rota = getDomain() + "/pedido/alterarSituacaoPedido";

        modalValidacao('Validação', 'Aguarde...');
        $.post(rota, {
            '_token': '<?php echo TOKEN; ?>',
            'id_pedido': idPedido,
            'id_situacao_pedido': idSituacaoPedido,

        }, function(resultado) {
            var retorno = JSON.parse(resultado);
            if (retorno.status == true) {
                setTimeout(modalValidacaoClose, 800);
            }
        })

        pedidos();
        return false;
    }

    $("#buscar-pedidos").click(function() {
        pedidos();
        return false;
    });

    pedidos();

    function pedidos() {
        $('#append-pedidos').html('<br><center><h3>Carregando...</h3></center>');
        var rota = $('#form').attr('action');
        $.post(rota,

            $('#form').serialize(),

            function(resultado) {
                $('#append-pedidos').empty();
                $('#append-pedidos').append(resultado);
            });
    }

    function cupomFiscalModal(idPedido) {
        var rotaNotinha = getDomain() + "/pedido/cupomFiscal";
        modalValidacao('Validação', 'Aguarde, estamos gerando sua notinha...');

        $.post(rotaNotinha, {
            '_token': '<?php echo TOKEN; ?>',
            'id_pedido': idPedido,

        }, function(resultado) {
            var retorno = JSON.parse(resultado);
            document.getElementById("divTabela").innerHTML = '<table class="printable printer-ticket">' +
                '<thead>' +
                '<tr>' +
                '<th class="title thTracado" colspan="3"><b id="nomeEmpresa"></b>' +
                '<hr>' +
                '</th>' +
                '</tr>' +
                '<tr>' +
                '<th class="thTracado" colspan="5" id="previsaoEntrega"></th>' +
                '</tr>' +
                '<tr>' +
                '<th class="thTracado" colspan="5">' +
                '<b>CLIENTE: <label style="color: black;" id="cliente"></label> <label style="color: black;" id="mesa"> </label></b>' +
                '<br><b>TELEFONE: <label style="color: black;" id="celular"></label></b><br />' +
                '<b>ENDEREÇO: <label style="color: black;" id="endereco"></label></b>' +
                '</th>' +
                '</tr>'+
                '<tr>' +
                '<th class="ttu thTracado" colspan="5">' +
                '<b>Cupom não fiscal</b>' +
                '<hr>' +
                '</th>' +

                '</tr>' +
                '</thead>' +
                '<tbody>' +

                '<tr class="ttu">' +
                '<td colspan="1"><b>PRODUTO </b></td>' +
                '<td colspan="1"><b>PREÇO </b></td>' +
                '<td colspan="1" style="width: 2vh; padding-left:10px;"><b>QTD.</b></td>' +
                '<td colspan="1"><b>SUBTOTAL </b></td>' +
                '</tr>' +
                '<tr class="ttu">' +
                '<td colspan="1" class="pr-1"><b id="nomeproduto"></b></td>' +
                '<td colspan="1"><b id="preco"></b></td>' +
                '<td colspan="1" style="padding-left:20px;"><b id="quantidade"></b></td>' +
                '<td colspan="1"><b id="subtotal"></b></td>' +
                '</tr>' +
                '</tbody>' +
                '<tfoot>' +
                '<tr class="sup ttu p--0">' +
                '<hr>' +
                '<td colspan="3">' +
                '<hr>' +
                '<b>TOTAIS</b>' +
                '</td>' +
                '</tr>' +
                // '<tr class="ttu">' +
                // '<td colspan="2">Sub-total</td>' +
                // '<td align="right" id="subtotal"></td>' +
                // '</tr>' +
                '<tr class="ttu">' +
                '<td colspan="2"><b>FRETE</b></td>' +
                '<td align="right"><b id="valor_frete"></b></td>' +
                '</tr>' +
                '<tr class="ttu">' +
                '<td colspan="2"><b>DESCONTO</b></td>' +
                '<td align="right"><b id="valordesconto"></b></td>' +
                '</tr>' +
                '<tr class="ttu">' +
                '<td colspan="2"><b>TOTAL</b></td>' +
                '<td align="right">' +
                '<b id="totalGeral"></b>' +
                '</td>' +
                '</tr>' +
                '<tr class="sup ttu p--0">' +
                '<td colspan="3">' +
                '<hr>' +
                '<b>PAGAMENTOS</b>' +
                '</td>' +
                '</tr>' +
                '<tr class="ttu">' +
                '<td colspan="2"><b>FORMA PAGAMENTO</b></td>' +
                '<td align="right"><b id="forma_pagamento"></b></td>' +
                '</tr>' +
                // '<tr class="ttu">' +
                // '<td colspan="2">Voucher</td>' +
                // '<td id="voucher" align="right">R$0,00</td>' +
                // '</tr>' +
                // '<tr class="ttu">' +
                // '<td colspan="2">Dinheiro</td>' +
                // '<td align="right">R$0,00</td>' +
                // '</tr>' +
                '<tr class="ttu">' +
                '<td colspan="2"><b>TOTAL PAGO</b></td>' +
                '<td align="right"><b class="campo-moeda" id="totalpago"></b></td>' +
                '</tr>' +
                '<tr class="ttu">' +
                '<td colspan="2"><b>TROCO</b></td>' +
                '<td align="right"><b id="valor_troco"></b></td>' +
                '</tr>' +
                '<tr class="sup">' +
                '<td colspan="3" align="center">' +
                '<hr>' +
                '<b>OBSERVAÇÃO:</b>' +
                '</td>' +
                '</tr>'+
                '<tr>'+
                '<td colspan="3" align="center">' +
                '<hr>' +
                '<b id="observacao_pedido"></b>' +
                '</td>' +
                '</tr>' +
                '<tr class="sup">' +
                '<td colspan="3" align="center"> - </td>' +
                '</tr>' +
                '</tfoot>' +
                '</table>';

            let contador = 0;
            var obspedido = retorno[0].observacao_pedido;

            for (let i = 0; i < retorno.length; i++) {
                console.log(retorno[i].nomeproduto);

                document.getElementById("nomeEmpresa").innerHTML = retorno[0].nomeEmpresa;
                document.getElementById("cliente").innerHTML = retorno[0].nomeCliente;
                document.getElementById("endereco").innerHTML = '<br>'+ retorno[0].endereco;
                document.getElementById("celular").innerHTML = retorno[0].celular;
                document.getElementById("nomeproduto").innerHTML += '<br>' + retorno[i].nomeproduto + '<br>';
                document.getElementById("preco").innerHTML += '<br> R$' + retorno[i].preco + '<br>';
                document.getElementById("quantidade").innerHTML += '<br>' + retorno[i].quantidade + '<br>';
                document.getElementById("subtotal").innerHTML += '<br> R$' + retorno[i].subtotal + '<br>';
                document.getElementById("totalGeral").innerHTML = 'R$'+retorno[i].totalGeral;
                document.getElementById("totalpago").innerHTML = 'R$'+retorno[i].totalpago;
                document.getElementById("forma_pagamento").innerHTML = retorno[0].forma_pagamento;
                document.getElementById("valor_troco").innerHTML = 'R$'+retorno[0].valor_troco;
                document.getElementById("valor_frete").innerHTML = 'R$'+retorno[0].valor_frete;
                document.getElementById("valordesconto").innerHTML = 'R$'+retorno[0].valordesconto;

                if ((obspedido == null) || (obspedido == '')) {
                    document.getElementById("observacao_pedido").innerHTML = 'Sem observações';
                } else {
                    document.getElementById("observacao_pedido").innerHTML = retorno[0].observacao_pedido;
                }

                $("#clicaModalCupom").trigger("click");
                setTimeout(modalValidacaoClose, 800);
            }
        });
    }
</script>