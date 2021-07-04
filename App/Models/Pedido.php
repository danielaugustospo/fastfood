<?php

namespace App\Models;

use System\Model\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $timestamps = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function pedidos($idVendedor = false, $idCliente = false, $ativos = null, $situacaoPedido = null, $clienteEndereco = null, $date = null, $idPedido = null)
    {
        $queryPorCliente = false;

        if ($idCliente) {
            $queryPorCliente = "AND (pedidos.id_cliente = {$idCliente})";
        }

        if ($ativos) {
            $queryPorCliente .= "AND (pedidos.deleted_at IS NOT NULL)";
        }

        if ($situacaoPedido) {
            $queryPorCliente .= "AND (pedidos.id_situacao_pedido = {$situacaoPedido})";
        }

        if ($date && $date['tipo'] === 'pedido') {
            $queryPorCliente .= "AND (pedidos.created_at >= '{$date['de']}' AND pedidos.created_at <= '{$date['ate']}')";
        }

        if ($date && $date['tipo'] === 'entrega') {
            $queryPorCliente .= "AND (pedidos.previsao_entrega >= '{$date['de']}' AND pedidos.previsao_entrega <= '{$date['ate']}')";
        }
        
        if ($idPedido) {
            $queryPorCliente .= "AND (pedidos.id = {$idPedido})";
        }

        return $this->query(
            "SELECT pedidos.id AS idPedido, pedidos.id_vendedor, pedidos.id_empresa, produtos.nome as nomeproduto, produtos_pedidos.id_produto, pedidos.valor_desconto, pedidos.valor_frete, pedidos.observacao_pedido, pedidos.valor_troco,
            produtos_pedidos.preco, produtos_pedidos.quantidade, produtos_pedidos.subtotal, clientes.nome AS nomeCliente, clientes.celular as celular,
            empresas.nome AS nomeEmpresa,
            IF(pedidos.previsao_entrega = '1970-01-01', 'Não informado', DATE_FORMAT(pedidos.previsao_entrega, '%d/%m/%Y')) AS previsaoEntrega,
             pedidos.data_compensacao, pedidos.id_meio_pagamento, pedidos.id_situacao_pedido, 
            mesas.descricao as mesa,
            CONCAT(clientes_enderecos.endereco , 
            ', Num: ', clientes_enderecos.numero, 
            ',<br> Complemento: ', clientes_enderecos.complemento, 
            ',<br> Bairro: ',clientes_enderecos.bairro, 
            ', Cidade: ',clientes_enderecos.cidade) as endereco,
            pedidos.valor_desconto AS valordesconto,
            situacao.legenda AS situacao,
            pagamento.legenda AS forma_pagamento,
  
            FORMAT((SELECT SUM(subtotal) FROM produtos_pedidos
              WHERE produtos_pedidos.id_pedido = pedidos.id
            ) + pedidos.valor_frete - pedidos.valor_desconto,2) AS totalGeral,

            FORMAT((SELECT SUM(subtotal) FROM produtos_pedidos
              WHERE produtos_pedidos.id_pedido = pedidos.id
            ) +  pedidos.valor_troco + pedidos.valor_frete - pedidos.valor_desconto,2) AS totalpago

  
            FROM pedidos INNER JOIN clientes ON pedidos.id_cliente = clientes.id
            LEFT JOIN situacoes_pedidos AS situacao ON pedidos.id_situacao_pedido = situacao.id
            LEFT JOIN meios_pagamentos AS pagamento ON pagamento.id = pedidos.id_meio_pagamento
            left join clientes_enderecos on pedidos.id_cliente_endereco = clientes_enderecos.id 
            left join mesas on pedidos.id_mesa = mesas.id
            left join produtos_pedidos on pedidos.id = produtos_pedidos.id_pedido
            left join produtos on produtos_pedidos.id_produto = produtos.id
            left join empresas on pedidos.id_empresa = empresas.id
          WHERE ( pedidos.id_vendedor = {$idVendedor} {$queryPorCliente} ) ORDER BY pedidos.id DESC"
        );
        
    }
}
