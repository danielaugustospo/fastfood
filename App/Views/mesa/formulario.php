<?php use System\Session\Session; ?>
<form method="post"
      action="<?php echo isset($mesa->id) ? BASEURL . '/mesa/update' : BASEURL . '/mesa/save'; ?>"
      enctype='multipart/form-data'>
    <!-- token de segurança -->
    <input type="hidden" name="_token" value="<?php echo TOKEN; ?>"/>

    <div class="row">

        <?php if (isset($mesa->id)) : ?>
            <input type="hidden" name="id" value="<?php echo $mesa->id; ?>">
        <?php endif; ?>

        <?php 
        $idPermissao = Session::get('idPerfil'); 
        if ($idPermissao == 1){ ?>

        <div class="col-md-4">
            <div class="form-group">
                <label for="password">Empresa *</label>
                <select class="form-control" name="id_empresa" id="id_empresa">
                    <option>Selecione...</option>
                    <?php foreach ($empresas as $empresa) : ?>
                        <?php if (isset($mesa->id) && $mesa->id_empresa == $empresa->id) : ?>
                            <option value="<?php echo $empresa->id; ?>"
                                    selected="selected"><?php echo $empresa->nome; ?>
                            </option>
                        <?php else : ?>
                            <option value="<?php echo $empresa->id; ?>"><?php echo $empresa->nome; ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php } else { ?>
            <input type="hidden" name="id_empresa" value="<?php echo Session::get('idEmpresa'); ?>">
        <?php } ?>

        <div class="col-md-4">
            <div class="form-group">
                <label for="nome">Nome Empresa*</label>
                <input type="text" class="form-control" name="nomemesa" id="nomemesa" placeholder="Digite o nome da mesa!"
                       value="<?php echo isset($mesa->id) ? $mesa->nomemesa : '' ?>">
            </div>
        </div>

        <!--
          Se o mesa logado for o mesmo que será editado, não mostra o campo de perfis porque
          um mesa não deve mudar o seu proprio perfil de mesa.
        -->


    </div>
    <!--end row-->

    <button type="submit" class="btn btn-success btn-sm button-salvar-mesa" style="float:right">
        <i class="fas fa-save"></i> Salvar
    </button>
</form>


<script>
    // Anula duplo click em salvar
    anulaDuploClick($('form'));

    // function verificaSeEmailExiste(email, id) {
    //     var rota = getDomain() + "/mesa/verificaSeEmailExiste";
    //     if (id) {
    //         rota += '/' + in64(email.value) + '/' + id;
    //     } else {
    //         rota += '/' + in64(email.value);
    //     }

    //     $.get(rota, function (data, status) {
    //         var retorno = JSON.parse(data);

    //         if (retorno.status == true) {
    //             modalValidacao('Validação', 'Este Email já existe! Por favor, informe outro!');
    //             $('.button-salvar-mesa').attr('disabled', 'disabled');
    //             $('.label-email').html('<small style="color:#cc0000!important">Este Email já existe!</small>');
    //         } else {
    //             $('.button-salvar-mesa').attr('disabled', false);
    //             $('.label-email').html('');
    //         }
    //     });
    // }
</script>
