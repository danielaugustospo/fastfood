<!--Usando o Html Components-->
<?php use System\HtmlComponents\Modal\Modal; ?>
<style>

.imagem-empresa {
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
    #containerModalImagemProduto img {
        display:block;
        margin:0 auto;
    }
    .imagem-empresa:hover {
        border:1px solid #009966;
        cursor:pointer;
</style>
<div class="row">

    <div class="card col-lg-12 content-div">
        <div class="card-body">
            <h5 class="card-title"><i class="fas fa-store"></i> Empresas</h5>
        </div>

        <table id="example" class="table tabela-ajustada table-striped" style="width:100%">
            <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Data</th>
                <th>Plano</th>
                <th>Status</th>
                <th style="text-align:right;padding-right:0">
                    <?php $rota = BASEURL . '/empresa/modalFormulario'; ?>
                    <button onclick="modalFormularioEmpresa('<?php echo $rota; ?>', null);"
                            class="btn btn-sm btn-success">
                        <i class="fas fa-plus"></i>

                    </button>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($empresas as $empresa): ?>
                <tr>
                    <td>
                    <?php if (!is_null($empresa->logo_empresa) && $empresa->logo_empresa != ''): ?>
                            <center>
                                <?php $logo_empresa = BASEURL . '/public/' . $empresa->logo_empresa;
                                //imagem/produtos/1621964203.jpg
                                ?> 
                                <img src="<?php echo $logo_empresa; ?>" width="40"
                                    class="imagem-empresa" title="Visualizar Imagem!"
                                    onclick="modalImagemDoProduto('<?php echo $logo_empresa;?>', '<?php echo $empresa->nome;?>')">
                            </center>
                        <?php else: ?>
                            <center><i class="fas fa-box-open" style="font-size:25px"></i></center>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $empresa->nome; ?></td>
                    <td><?php echo $empresa->email; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($empresa->created_at)); ?></td>
                    <td><?php echo $empresa->id_planos; ?></td>
                    <td>
                        <?php if( $empresa->plano_ativo == 1) : echo '<span class="badge badge-secondary">Ativo</span>'; else: echo '<span class="badge badge-danger">Bloqueado</span>'; endif; ?>
                    </td>

                    <td style="text-align:right">
                        <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-secondary dropdown-toggle"
                                onclick="modalFormularioEmpresa('<?php echo $rota; ?>', '<?php echo $empresa->id; ?>');">
                            <i class="fas fa-cogs"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tfoot></tfoot>
        </table>

        <br>

    </div>
</div>

<?php Modal::start([
    'id' => 'modalEmpresa',
    'width' => 'modal-lg',
    'title' => 'Cadastrar Empresa'
]); ?>

<div id="formulario"></div>

<?php Modal::stop(); ?>

<script>
    function modalFormularioEmpresa(rota, id) {
        var url = "";

        if (id) {
            url = rota + "/" + id;
        } else {
            url = rota;
        }

        $("#formulario").html("<center><h3>Carregando...</h3></center>");
        $("#modalEmpresa").modal({backdrop: 'static'});
        $("#formulario").load(url);
    }
</script>
