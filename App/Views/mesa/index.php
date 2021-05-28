<!--Usando o Html Components-->
<?php

use App\Config\ConfigPerfil;
use System\HtmlComponents\FlashMessage\FlashMessage;
use System\HtmlComponents\Modal\Modal;
use System\Session\Session;

?>

<style type="text/css">
    .imagem-perfil {
        width: 40px;
        height: 40px;
        object-fit: cover;
        object-position: center;
        border-radius: 50%;
    }
</style>

<div class="row">

    <div class="card col-lg-12 content-div">
        <div class="card-body">
            <h5 class="card-title"><i class="fas fa-users"></i> Mesas</h5>
        </div>
        <!-- Mostra as mensagens de erro-->
        <?php FlashMessage::show(); ?>

        <?php if (count($mesas) > 0): ?>
            <table id="example" class="table tabela-ajustada table-striped" style="width:100%">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th style="text-align:right;padding-right:0">
                        <?php $rota = BASEURL . '/mesa/modalFormulario'; ?>
                        <?php if (Session::get('idPerfil') != ConfigPerfil::vendedor()): ?>
                            <button onclick="modalMesas('<?php echo $rota; ?>', false);"
                                    class="btn btn-sm btn-success" title="Nova Mesa!">
                                <i class="fas fa-plus"></i>
                            </button>
                        <?php endif; ?>
                    </th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($mesas as $mesa): ?>
                    <tr>

                        <td><?php echo $mesa->nomemesa; ?></td>
                        <td style="text-align:right">

                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button"
                                        class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-cogs"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                                    <button class="dropdown-item" href="#"
                                            onclick="modalMesas('<?php echo $rota; ?>', <?php echo $mesa->id; ?>);">
                                        <i class="fas fa-edit"></i> Editar
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
        <?php else: ?>
            <center>
                <i class="far fa-grin-beam" style="font-size:50px;opacity:0.60"></i> <br> <br>
                Poxa, ainda não há nenhuma Mesa cadastrada! <br>
                <?php $rota = BASEURL . '/mesa/modalFormulario'; ?>
                <button
                    onclick="modalMesas('<?php echo $rota; ?>', null);"
                    class="btn btn-sm btn-success">
                    <i class="fas fa-plus"></i>
                    Cadastrar Mesa
                </button>
            </center>
        <?php endif; ?>

        <br>

    </div>
</div>

<?php Modal::start([
    'id' => 'modalMesas',
    'width' => 'modal-lg',
    'title' => 'Cadastrar Mesas'
]); ?>

<div id="formulario"></div>

<?php Modal::stop(); ?>

<script>
    function modalMesas(rota, mesaId) {
        var url = "";

        if (mesaId) {
            url = rota + "/" + mesaId;
        } else {
            url = rota;
        }

        $("#modalMesas").modal({backdrop: 'static'});
        $("#formulario").load(url);
    }
</script>
