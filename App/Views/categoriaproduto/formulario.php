<form method="post" action="<?php echo isset($categoriaProduto->id) ? BASEURL . '/categoriaproduto/update' : BASEURL . '/categoriaproduto/save'; ?>" enctype='multipart/form-data'>
    <div class="row">

        <input type="hidden" name="_token" value="<?php echo TOKEN; ?>" />

        <?php if (isset($categoriaProduto->id)) : ?>
            <input type="hidden" name="id" value="<?php echo $categoriaProduto->id; ?>">
        <?php endif; ?>

        <input type="hidden" name="id_empresa" value="1">



        <div class="col-md-4">
            <div class="form-group">
                <label for="foto">Escolher Imagem da Categoria</label>
                <input type="file" class="form-control" name="foto" id="foto"> <br>
                <?php if (isset($categoriaProduto->id) && !is_null($categoriaProduto->foto)) : ?>
                    <img src="<?php echo BASEURL . '/public/' . $categoriaProduto->foto; ?>" class="imagem-produto">
                <?php else : ?>
                    <i class="fas fa-box-open" style="font-size:40px"></i>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea class="form-control" name="descricao" id="descricao" placeholder="Deixe uma descrição do Produto!"><?php echo isset($categoriaProduto->id) ? $categoriaProduto->descricao : ''; ?></textarea>
            </div>
        </div>

    </div>
    <!--end row-->

    <div class="row">
        <div class="col-md-12">
            <div class="form-group" style="background:#fffcf5">
                <label for="ativo">
                    Ativo: <small style="opacity:0.80">Mostrar categoria de produto no PDV</small>
                    <input id="ativo" name="deleted_at" type="checkbox" class="form-control" <?php if (isset($categoriaProduto->id) && is_null($categoriaProduto->deleted_at)) : ?> checked <?php endif; ?> checked>
                </label>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-success btn-sm" style="float:right" onclick="return salvarCategoriaProduto()">
        <i class="fas fa-save"></i> Salvar
    </button>
</form>

<script>
    // Anula duplo click em salvar
    anulaDuploClick($('form'));

    // $(function () {
    //     jQuery('.campo-moeda')
    //         .maskMoney({
    //             prefix: 'R$ ',
    //             allowNegative: false,
    //             thousands: '.', decimal: ',',
    //             affixesStay: false
    //         });
    // });

    $("#ativo").click(function() {
        if (!$(this).is(':checked')) {
            modalValidacao('Validação', '<small>Ao desativar esta categoria, ela não será apresentada nas Vendas!</small>');
        }
    })
</script>