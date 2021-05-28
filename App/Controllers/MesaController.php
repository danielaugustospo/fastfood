<?php

namespace App\Controllers;

use App\Models\Empresa;
use App\Models\Perfil;
use App\Models\Sexo;
use App\Models\Mesa;
use App\Rules\Logged;
use App\Services\SendEmail\SendEmail;
use App\Services\UploadService\UploadFiles;
use Exception;
use System\Controller\Controller;
use System\Get\Get;
use System\HtmlComponents\SendEmailTemplate\SimpleTemplate;
use System\Post\Post;
use System\Session\Session;

class MesaController extends Controller
{
    protected $post;
    protected $get;
    protected $layout;
    protected $idEmpresa;
    protected $idMesaLogado;
    protected $idPerfilMesaLogado;


    public function __construct()
    {
        parent::__construct();
        $this->layout = 'default';

        $this->post = new Post();
        $this->get = new Get();
        $this->idEmpresa = Session::get('idEmpresa');
        $this->idMesaLogado = Session::get('idMesa');
        $this->idPerfilMesaLogado = session::get('idPerfil');

        $logged = new Logged();
        $logged->isValid();
    }

    public function index()
    {
        $mesa = new Mesa();
        $mesas = $mesa->mesas(
            $this->idEmpresa,
            $this->idMesaLogado,
            $this->idPerfilMesaLogado
        );
// var_dump($mesas);
        $this->view('mesa/index', $this->layout, compact('mesas'));
    }

    public function save()
    {
        if ($this->post->hasPost()) {
            $mesa = new Mesa();
            $dados = (array)$this->post->data();
            $dados['password'] = createHash($dados['password']);

            # Valida imagem somente se existir no envio
            if (!empty($_FILES["imagem"]['name'])) {

                $diretorioImagem = false;
                if ($this->diretorioImagemMesaNoEnv && !is_null($this->diretorioImagemMesaNoEnv)) {
                    $diretorioImagem = $this->diretorioImagemMesaNoEnv;
                } else {
                    $diretorioImagem = $this->diretorioImagemMesaPadrao;
                }

                $retornoImagem = uploadImageHelper(
                    new UploadFiles(),
                    $diretorioImagem,
                    $_FILES["imagem"]
                );

                # Verifica se houve erro durante o upload de imagem
                if (is_array($retornoImagem)) {
                    Session::flash('error', $retornoImagem['error']);
                    return $this->get->redirectTo("mesa");
                }

                $dados['imagem'] = $retornoImagem;
            }

            try {
                # Cadastra Usuário
                $mesa->save($dados);
                return $this->get->redirectTo("mesa");

            } catch (Exception $e) {
                dd('Erro ao cadastrar Mesa ' . $e->getMessage());
            }
        }
    }

    public function update()
    {
        if ($this->post->hasPost()) {
            $mesa = new Mesa();
            $dadosMesa = $mesa->find($this->post->data()->id);

            $dados = (array)$this->post->only([
                'nome', 'email', 'password',
                'id_sexo', 'id_perfil'
            ]);



            if (!is_null($this->post->data()->password)) {
                $dados['password'] = createHash($this->post->data()->password);
            } else {
                unset($dados['password']);
            }

            try {
                $mesa->update($dados, $dadosMesa->id);
                return $this->get->redirectTo("mesa");

            } catch (Exception $e) {
                dd($e->getMessage());
            }
        }
    }

    public function modalFormulario($idMesa)
    {
        $sexo = new Sexo();
        $sexos = $sexo->all();

        $perfil = new Perfil();
        $perfis = $perfil->perfis(false, false, Session::get('idPerfil'));

        $mesa = false;
        if ($idMesa) {
            $mesa = new Mesa();
            $mesa = $mesa->find($idMesa);

            $perfis = $perfil->perfis(
                $this->idMesaLogado,
                $idMesa,
                Session::get('idPerfil')
            );
        }

        $empresa = new Empresa();
        $empresas = $empresa->retornaEmpresas();

        $this->view('mesa/formulario', null,
            compact(
                'sexos',
                'mesa',
                'perfis',
                'empresas'
            ));
    }


}
