<?php

namespace App\Controllers;

use App\Models\CategoriaProduto;
use App\Models\MeioPagamento;
use App\Models\Produto;
use App\Models\Venda;
use App\Repositories\VendasEmSessaoRepository;
use App\Rules\AcessoAoTipoDePdv;
use App\Rules\Logged;
use System\Controller\Controller;
use System\Get\Get;
use System\Post\Post;
use System\Session\Session;

class PdvDiferencialController extends Controller
{
    protected $post;
    protected $get;
    protected $layout;
    protected $idEmpresa;
    protected $idUsuario;
    protected $idPerfilUsuarioLogado;
    protected $vendasEmSessaoRepository;

    public function __construct()
    {
        parent::__construct();
        $this->layout = 'default';

        $this->post = new Post();
        $this->get = new Get();
        $this->idEmpresa = Session::get('idEmpresa');
        $this->idUsuario = Session::get('idUsuario');
        $this->idPerfilUsuarioLogado = Session::get('idPerfil');

        $this->vendasEmSessaoRepository = new VendasEmSessaoRepository();

        $logged = new Logged();
        $logged->isValid();

        $acessoAoTipoDePdv = new AcessoAoTipoDePdv();
        $acessoAoTipoDePdv->validate();
    }

    public function index()
    {
        $meioPagamento = new MeioPagamento();
        $meiosPagamentos = $meioPagamento->all();

        $produto = new Produto();
        $produtos = $produto->produtos($this->idEmpresa);


        $categoriaProduto = new CategoriaProduto();
        $categoriaProdutos = $categoriaProduto->categoriaprodutos($this->idEmpresa);

        $this->view('pdv/diferencial', $this->layout,
            compact(
                'meiosPagamentos',
                'produtos',
                'categoriaProdutos'
            ));
    }

    public function saveVendasViaSession()
    {
        if (!isset($_SESSION['venda']) ||empty($_SESSION['venda'])) {
            return;
        }

        $status = false;
        foreach ($_SESSION['venda'] as $produto) {
            $meioDePagamento = $this->post->data()->id_meio_pagamento;
            $dataCompensacao = null;

            // s?? adiciona caso seja um boleto
            // if ($meioDePagamento == 4) {
            //     $dataCompensacao = $this->post->data()->data_compensacao;
            // }

            $dados = [
                'id_usuario' => $this->idUsuario,
                'id_meio_pagamento' => $meioDePagamento,
                // 'data_compensacao' => $dataCompensacao,
                'data_compensacao' => '1970-01-01',
                'id_empresa' => $this->idEmpresa,
                'id_produto' => $produto['id'],
                'preco' => $produto['preco'],
                'quantidade' => $produto['quantidade'],
                'valor' => $produto['total']
            ];

            $venda = new Venda();
            try {
                $venda->save($dados);
                $status = true;

                unset($_SESSION['venda']);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }

        echo json_encode(['status' => $status]);
    }

    public function colocarProdutosNaMesa($idProduto)
    {
        return $this->vendasEmSessaoRepository->colocarProdutosNaMesa($idProduto);
    }

    public function obterProdutosDaMesa($posicaoProduto)
    {
        echo $this->vendasEmSessaoRepository->obterProdutosDaMesa($posicaoProduto);
    }

    public function alterarAquantidadeDeUmProdutoNaMesa($idProduto, $quantidade)
    {
        $this->vendasEmSessaoRepository->alterarAquantidadeDeUmProdutoNaMesa($idProduto, $quantidade);
    }

    public function retirarProdutoDaMesa($idProduto)
    {
        $this->vendasEmSessaoRepository->retirarProdutoDaMesa($idProduto);
    }

    public function obterValorTotalDosProdutosNaMesa()
    {
        echo $this->vendasEmSessaoRepository->obterValorTotalDosProdutosNaMesa();
    }
}
