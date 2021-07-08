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
                text-transform: uppercase;
            }

            .screen {
                display: none;
            }
        }
    }
    p,h1,h2,h3,h4,h5,h6{
        text-transform: uppercase;
    }
</style>


<div class="modal fade" id="modalCupomFiscal" tabindex="-1" role="dialog" aria-labelledby="modalCupomFiscal" aria-hidden="true" style="overflow: scroll;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                
                <h5 class="modal-title" id="exampleModalLongTitle">Cupom não Fiscal</h5>
                <button type="button" class="btn btn-primary ml-5 mt-0 p-1" id="btnPrint"><i class="fa fa-print pt-2 mr-1" aria-hidden="true"></i>Imprimir</button>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="divTabela">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <!-- <button type="button" class="btn btn-primary" id="btnPrint">Imprimir</button> -->
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

    $(function() {
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