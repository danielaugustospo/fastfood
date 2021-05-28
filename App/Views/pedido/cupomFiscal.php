<style>
    @color-gray: #BCBCBC;

    .text {
        &-center {
            text-align: center;
        }
    }

    .ttu {
        text-transform: uppercase;
    }

    .printer-ticket {
        display: table !important;
        width: 100%;
        max-width: 400px;
        font-weight: light;
        line-height: 1.3em;
        @printer-padding-base: 10px;

        &,
        & * {
            font-family: Tahoma, Geneva, sans-serif;
            font-size: 10px;
        }

        /* th:nth-child(2), */
        td:nth-child(2) {
            width: 50px;
        }

        /* th:nth-child(3), */
        td:nth-child(3) {
            width: 90px;
            text-align: right;
        }

        .thTracado {
            font-weight: inherit;
            padding: @printer-padding-base 0;
            text-align: center;
            border-bottom: 1px dashed @color-gray;
        }

        tbody {
            tr:last-child td {
                padding-bottom: @printer-padding-base;
            }
        }

        tfoot {
            .sup td {
                padding: @printer-padding-base 0;
                border-top: 1px dashed @color-gray;
            }

            .sup.p--0 td {
                padding-bottom: 0;
            }
        }

        .title {
            font-size: 1.5em;
            padding: @printer-padding-base*1.5 0;
        }

        .top {
            td {
                padding-top: @printer-padding-base;
            }
        }

        .last td {
            padding-bottom: @printer-padding-base;
        }

        .printable {
            display: none;
        }

        /* print styles*/
        @media print {
            .printable {
                display: block;
            }

            .screen {
                display: none;
            }
        }
    }
</style>

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cumpom não Fiscal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="printable printer-ticket">
                    <thead>
                        <tr>
                            <th class="title thTracado" colspan="3">
                            <p id="01">Nome da Empresa</p>
                                <hr>
                            </th>
                        </tr>

                        <tr>
                            <th class="thTracado" colspan="3" id="previsaoentrega"></th>
                        </tr>

                        <tr>
                            <th class="thTracado" colspan="3">
                            <p>Cliente: <label style="color: black;" id="cliente"></label> - <label style="color: black;" id="mesa"></label></p><br /> 
                            <p>Endereço: <label style="color: black;" id="endereco"></label></p> 
                            </th>
                        </tr>
                        <tr>
                            <th class="ttu thTracado" colspan="3">
                                <b>Cupom não fiscal</b>
                                <hr>
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr class="top">
                            <td colspan="3" id="nomeproduto">Total em Produtos</td>
                        </tr>
                        <tr>
                            <td id="preco"></td>
                            <td id="quantidade"></td>
                            <td id="subtotal"></td>
                        </tr>
                        <!-- <tr>
                            <td colspan="3">Opcional Adicicional: grande</td>
                        </tr>
                        <tr>
                            <td>R$0,33</td>
                            <td>2.0</td>
                            <td>R$0,66</td>
                        </tr> -->
                    </tbody>
                    <tfoot>
                        <tr class="sup ttu p--0">
                            <td colspan="3">
                                <hr>
                                <b>Totais</b>
                            </td>
                        </tr>
                        <tr class="ttu">
                            <td colspan="2">Sub-total</td>
                            <td align="right" id="subtotal"></td>
                        </tr>
                        <tr class="ttu">
                            <td colspan="2">Taxa de serviço</td>
                            <td align="right">R$0,00</td>
                        </tr>
                        <tr class="ttu">
                            <td colspan="2">Desconto</td>
                            <td align="right">0,00%</td>
                        </tr>
                        <tr class="ttu">
                            <td colspan="2">Total</td>
                            <td align="right"><p id="totalgeral"></p></td>
                        </tr>
                        <tr class="sup ttu p--0">
                            <td colspan="3">
                                <hr>
                                <b>Pagamentos</b>
                            </td>
                        </tr>
                        <tr class="ttu">
                            <td colspan="2">Voucher</td>
                            <td align="right">R$0,00</td>
                        </tr>
                        <tr class="ttu">
                            <td colspan="2">Dinheiro</td>
                            <td align="right">R$0,00</td>
                        </tr>
                        <tr class="ttu">
                            <td colspan="2">Total pago</td>
                            <td align="right" id="totalpago">R$0,00</td>
                        </tr>
                        <tr class="ttu">
                            <td colspan="2">Troco</td>
                            <td align="right">R$0,00</td>
                        </tr>
                        <tr class="sup">
                            <td colspan="3" align="center">
                                <hr>
                                <b>Pedido:</b>
                            </td>
                        </tr>
                        <tr class="sup">
                            <td colspan="3" align="center">
                               -
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" id="btnPrint">Imprimir</button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo BASEURL; ?>/public/js/printThis.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#btnPrint").click(function() {
            //get the modal box content and load it into the printable div
            $(".printable").html($("#myModal").html());
            $(".printable #btnPrint").remove();
            $(".printable").printThis();
        });
    });

    $(function(){
        $(document).on('click', '.clicador', function(e) {
            e.preventDefault;
            var idpedido = $(this).closest('tr').find('td[data-idpedido]').data('idpedido');
            var nomecliente = $(this).closest('tr').find('td[data-nomecliente]').data('nomecliente');
            var totalgeral = $(this).closest('tr').find('td[data-totalgeral]').data('totalgeral');

            var mesa = $(this).closest('tr').find('td[data-mesa]').data('mesa');
            var endereco = $(this).closest('tr').find('td[data-endereco]').data('endereco');

//             var id = $('#botaoModal').data('todo').id;
// var cliente = $('#botaoModal').data('todo').cliente;
// var mesa = $('#botaoModal').data('todo').mesa;
// var totalgeral = $('#botaoModal').data('todo').totalgeral;


document.getElementById("cliente").innerHTML = nomecliente;
document.getElementById("mesa").innerHTML = mesa;
document.getElementById("totalgeral").innerHTML = totalgeral;
document.getElementById("subtotal").innerHTML = totalgeral;
document.getElementById("totalpago").innerHTML = totalgeral;
document.getElementById("endereco").innerHTML = endereco;



            // alert(totalgeral);
        });
    });



// var id = $('#botaoModal').data('todo').id;
// var cliente = $('#botaoModal').data('todo').cliente;
// var mesa = $('#botaoModal').data('todo').mesa;
// var totalgeral = $('#botaoModal').data('todo').totalgeral;


// document.getElementById("cliente").innerHTML = cliente;
// document.getElementById("mesa").innerHTML = mesa;
// document.getElementById("totalgeral").innerHTML = totalgeral;
</script>