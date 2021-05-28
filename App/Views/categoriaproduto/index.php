<!--Usando o Html Components-->
<?php

use System\HtmlComponents\FlashMessage\FlashMessage;
use System\HtmlComponents\Modal\Modal;
use System\Session\Session;

?>

<style type="text/css">
    .foto-categoriaproduto {
        width: 40px;
        height: 40px;
        object-fit: cover;
        object-position: center;
        border-radius: 50%;
        border: 1px solid silver;
    }
    .with_deleted_at {
        opacity:0.70;
    }
    #containerModalImagemCategoriaProduto img {
        display:block;
        margin:0 auto;
    }
    .foto-categoriaproduto:hover {
        border:1px solid #009966;
        cursor:pointer;
    }
</style>

<div class="row">

    <div class="card col-lg-12 content-div">
        <div class="card-body">
            <h5 class="card-title"><i class="fas fa-box-open"></i>Categorias de Produtos</h5>
        </div>
        <!-- Mostra as mensagens de erro-->
        <?php FlashMessage::show(); ?>

        <table class="table tabela-ajustada table-striped" style="width:100%">
            <thead>
            <tr>
                <th>#</th>
                <th>Descrição</th>
                <th>Visível no PDV</th>
               <!-- <th>R$ Preço</th> -->
                <?php
                    $idPermissao = Session::get('idPerfil');
                    if ($idPermissao != 4) { ?>
                <th style="text-align:right;padding-right:0">
                    <?php $rota = BASEURL . '/categoriaproduto/modalFormulario'; ?>
                    <button onclick="modalFormularioCategoriaProdutos('<?php echo $rota; ?>', false);"
                            class="btn btn-sm btn-success" title="Nova Categoria!">
                        <i class="fas fa-plus"></i>
                    </button>
                </th>
                <?php } ?>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($categoriaProdutos as $categoriaproduto): ?>
                <tr>
                    <td>
                        <?php if (!is_null($categoriaproduto->foto) && $categoriaproduto->foto != ''): ?>
                            <center>
                                <?php $foto = BASEURL . '/public/' . $categoriaproduto->foto; ?>
                                <img src="<?php echo $foto; ?>" width="40"
                                    class="foto-categoriaproduto" title="Visualizar Imagem!"
                                    onclick="modalImagemCategoriaDoProduto('<?php echo $foto;?>', '<?php echo $categoriaproduto->descricao;?>')">
                            </center>
                        <?php else: ?>
                            <center><i class="fas fa-box-open" style="font-size:25px"></i></center>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $categoriaproduto->descricao; ?></td>
                    <?php if (is_null($categoriaproduto->deleted_at)):?>
                        <td>Sim</td>
                    <?php else:?>
                        <td class="with_deleted_at">Não</td>
                    <?php endif;?>

                    <?php
                            $idPermissao = Session::get('idPerfil');
                            if ($idPermissao != 4) { ?>
                    <td style="text-align:right">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-secondary dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cogs"></i>
                            </button>


                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                                <button class="dropdown-item" href="#"
                                        onclick="modalFormularioCategoriaProdutos('<?php echo $rota; ?>', '<?php echo $categoriaproduto->id; ?>')">
                                    <i class="fas fa-edit"></i> Editar
                                </button>

                                <!--<a class="dropdown-item" href="#">
                                    <i class="fas fa-trash-alt" style="color:#cc6666"></i> Excluir
                                </a>-->

                            </div>
                        </div>
                    </td>
                    <?php } ?>

                </tr>
            <?php endforeach; ?>

            <tfoot></tfoot>
        </table>

        <br>

    </div>
</div>

<?php Modal::start([
    'id' => 'modalFormulario',
    'width' => 'modal-lg',
    'title' => 'Cadastro de Categoria de Produtos'
]); ?>

<div id="formulario"></div>

<?php Modal::stop(); ?>

<?php Modal::start([
    'id' => 'modalImagemCategoriaProduto',
    'width' => 'modal-lg',
    'title' => 'Imagem da Categoria'
]); ?>

<div id="containerModalImagemCategoriaProduto"></div>

<?php Modal::stop(); ?>

<script>
    function modalFormularioCategoriaProdutos(rota, id) {
        var url = "";

        if (id) {
            url = rota + "/" + id;
        } else {
            url = rota;
        }

        $("#formulario").html("<center><h3>Carregando...</h3></center>");
        $("#modalFormulario").modal({backdrop: 'static'});

        $("#formulario").load(url);
    }

    function salvarCategoriaProduto() {
        if ($('#descricao').val() == '') {
            modalValidacao('Validação', 'Campo (Nome/Descrição) deve ser preenchido!');
            return false;

        }
        return true;
    }

    function modalImagemCategoriaDoProduto(foto, descricao) {
        $("#modalImagemCategoriaProduto").modal().show();
        var html = '<center><h3>'+descricao+'<h3></center>';
            html += '<img src="'+foto+'"/>';
        $("#containerModalImagemCategoriaProduto").html(html);
    }
</script>
