<?php

namespace App\Models;

use App\Config\ConfigPerfil;
use System\Auth\Auth;
use System\Model\Model;

class Mesa extends Model
{
    use Auth;

    protected $table = 'mesas';
    protected $timestamps = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function mesas($idEmpresa, $idUsuarioLogado = false, $idPerfilUsuarioLogado = false)
    {
        $superAdmin = ConfigPerfil::superAdmin();
        $administrador = ConfigPerfil::adiministrador();
        $gerente = ConfigPerfil::gerente();
        $vendedor = ConfigPerfil::vendedor();

        # Se o perfil do Usuário logado não for (superAdmin), não traz Usuários com perfil (superAdmin)
        $queryCondicional = false;
        if ($idPerfilUsuarioLogado && $idPerfilUsuarioLogado != $superAdmin) {
            $queryCondicional = "WHERE mesas.id_empresa = {$idEmpresa}";
        }

        # Se o perfil do Usuário logado for de vendedor, mostra apenas os dados do proprio Usuário
        if ($idPerfilUsuarioLogado && $idPerfilUsuarioLogado == $vendedor) {
            $queryCondicional = "WHERE mesas.id_empresa = {$idEmpresa}";
        }

        if ($idPerfilUsuarioLogado && $idPerfilUsuarioLogado == $superAdmin) {
            $queryCondicional = false;
        }

        return $this->query(
            "SELECT
            mesas.id AS id,
            mesas.descricao as nomemesa,
      		pedidos.id_mesa as pedidomesa,
      		pedidos.id_situacao_pedido as situacao
            FROM mesas
            LEFT JOIN pedidos
			ON mesas.id = pedidos.id_mesa order by id {$queryCondicional}"
        );
    }


    public function usuariosPorIdEmpresa($idEmpresa)
    {
        return $this->query("SELECT * FROM mesas WHERE id_empresa = {$idEmpresa}");
    }
}
