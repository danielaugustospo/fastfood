<?php

namespace App\Models;

use System\Model\Model;

class Empresa extends Model
{
    protected $table = 'empresas';
    protected $timestamps = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function seDadoNaoPertenceAEmpresaEditado($nomeDoCampo, $valor, $idEmpresa)
    {
        $dadoEmpresa = $this->findBy("{$nomeDoCampo}", $valor);
        if ($dadoEmpresa && $idEmpresa != $dadoEmpresa->id) {
            return true;
        }

        return false;
    }

    public function verificaSeEmailExiste($email)
    {
        if (!$email) {
            return false;
        }

        $query = $this->query("SELECT * FROM empresas WHERE email = '{$email}'");
        if (count($query) > 0) {
            return true;
        }

        return false;
    }

    public function retornaEmpresas(){
        $query = $this->query("SELECT id, nome, email FROM empresas");
        if (count($query) > 0) {
            return $query;
        }

        return false;
    }
    public function permissaoAcessoEmpresas($idEmpresa){
        $query = $this->query("SELECT e.id, e.nome, e.email, e.plano_ativo, e.logo_empresa, pl.descricao FROM empresas e, planos pl WHERE e.plano_ativo = 1 and e.id = {$idEmpresa} and pl.id = e.id_planos");
        if (count($query) > 0) {
            return $query;
        }

        return false;
    }
}
