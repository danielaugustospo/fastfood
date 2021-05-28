<?php
namespace App\Controllers;

use App\Models\CategoriaProduto;
use App\Rules\Logged;
use App\Services\UploadService\UploadFiles;
use Exception;
use System\Controller\Controller;
use System\Get\Get;
use System\Post\Post;
use System\Session\Session;


ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

class CategoriaProdutoController extends Controller
{
    protected $post;
    protected $get;
    protected $layout;
    protected $idEmpresa;
    protected $diretorioImagemCategoriaProdutoNoEnv;
    protected $diretorioImagemCategoriaProdutoPadrao;

    public function __construct()
    {
        parent::__construct();
        $this->layout = 'default';

        $this->diretorioImagemCategoriaProdutoPadrao = 'public/imagem/categoriaProdutos/';
        # Pega o diretório setado no .env
        $this->diretorioImagemCategoriaProdutoNoEnv = getenv('DIRETORIO_IMAGENS_PRODUTO');

        $this->post = new Post();
        $this->get = new Get();
        $this->idEmpresa = Session::get('idEmpresa');

        $logged = new Logged();
        $logged->isValid();
    }

    public function index()
    {
        $categoriaProduto = new CategoriaProduto();
        $categoriaProdutos = $categoriaProduto->categoriaProdutos($this->idEmpresa);

        $this->view('categoriaProduto/index', $this->layout, compact('categoriaProdutos'));
    }

    public function save()
    {
        if ($this->post->hasPost()) {
            $categoriaProduto = new CategoriaProduto();
            $dados = (array) $this->post->data();

            $dados['id_empresa'] = $this->idEmpresa;
            // $dados['preco'] = formataValorMoedaParaGravacao($dados['preco']);

            if ( ! isset($dados['deleted_at'])) {
                $dados['deleted_at'] = timestamp();
            } else {
                # Retira o deleted_at do array para que seja cadastrado como null no banco
                unset($dados['deleted_at']);
            }

            # Valida imagem somente se existir no envio
            if (!empty($_FILES["foto"]['name'])) {

                $diretorioImagem = false;
                if ($this->diretorioImagemCategoriaProdutoNoEnv && !is_null($this->diretorioImagemCategoriaProdutoNoEnv)) {
                    $diretorioImagem = $this->diretorioImagemCategoriaProdutoNoEnv;
                } else {
                    $diretorioImagem = $this->diretorioImagemCategoriaProdutoPadrao;
                }

                $diretorioImagem = 'imagem/categoriaProdutos/';
                $retornoImagem = uploadImageHelper(
                    new UploadFiles(),
                    $diretorioImagem,
                    $_FILES["foto"]
                );

                # Verifica de houve erro durante o upload de imagem
                if (is_array($retornoImagem)) {
                    Session::flash('error', $retornoImagem['error']);
                    return $this->get->redirectTo("categoriaproduto");
                }

                $dados['foto'] = $retornoImagem;


            }

            try {
                $categoriaProduto->save($dados);

                return $this->get->redirectTo("categoriaproduto");

            } catch (Exception $e) {
                dd($e->getMessage());
            }
        }
    }

    public function update()
    {
        if ($this->post->hasPost()) {
            $categoriaProduto = new CategoriaProduto();
            $dadosCategoriaProduto = $categoriaProduto->find($this->post->data()->id);

            $dados = (array)$this->post->only([
                'descricao'
            ]);

            if ( ! isset($this->post->data()->deleted_at)) {
                $dados['deleted_at'] = timestamp();
            } else {
                # Retira o deleted_at do array para que seja cadastrado como null no banco
                unset($dados['deleted_at']);
            }

            // $dados['preco'] = formataValorMoedaParaGravacao($dados['preco']);

            if (!empty($_FILES["imagem"]['name'])) {

                if (file_exists($dadosCategoriaProduto->imagem)) {
                    # Deleta a imagem anterior
                    unlink($dadosCategoriaProduto->imagem);
                }

                $diretorioImagem = false;
                if ($this->diretorioImagemCategoriaProdutoNoEnv && !is_null($this->diretorioImagemCategoriaProdutoNoEnv)) {
                    $diretorioImagem = $this->diretorioImagemCategoriaProdutoNoEnv;
                } else {
                    $diretorioImagem = $this->diretorioImagemCategoriaProdutoPadrao;
                }
                $diretorioImagem = 'imagem/categoriaProdutos/';

                $retornoImagem = uploadImageHelper(
                    new UploadFiles(),
                    $diretorioImagem,
                    $_FILES["imagem"]
                );

                # Verifica de houve erro durante o upload de imagem
                if (is_array($retornoImagem)) {
                    Session::flash('error', $retornoImagem['error']);
                    return $this->get->redirectTo("categoriaproduto");
                }

                $dados['imagem'] = $retornoImagem;
            }

            try {
                $categoriaProduto->update($dados, $dadosCategoriaProduto->id);
                return $this->get->redirectTo("categoriaproduto");

            } catch (Exception $e) {
                dd($e->getMessage());
            }
        }
    }

    public function modalFormulario($idCategoriaProduto)
    {
        $categoriaProduto = false;

        if ($idCategoriaProduto) {
            $categoriaProduto = new CategoriaProduto();
            $categoriaProduto = $categoriaProduto->find($idCategoriaProduto);
        }

        $this->view('categoriaProduto/formulario', null, compact('categoriaProduto'));
    }
}
