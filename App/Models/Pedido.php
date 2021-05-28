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

    public function pedidos($idVendedor = false, $idCliente = false, $ativos = null, $situacaoPedido = null, $date = null)
    {
        $queryPorCliente = false;

        if ($idCliente) {
            $queryPorCliente = "AND pedidos.id_cliente = {$idCliente}";
        }

        if ($ativos) {
            $queryPorCliente = "AND pedidos.deleted_at IS NOT NULL";
        }

        if ($situacaoPedido) {
            $queryPorCliente = "AND pedidos.id_situacao_pedido = {$situacaoPedido}";
        }

        if ($date && $date['tipo'] === 'pedido') {
            $queryPorCliente = "AND pedidos.created_at >= '{$date['de']}' AND pedidos.created_at <= '{$date['ate']}'";
        }

        if ($date && $date['tipo'] === 'entrega') {
            $queryPorCliente = "AND pedidos.previsao_entrega >= '{$date['de']}' AND pedidos.previsao_entrega <= '{$date['ate']}'";
        }

        return $this->query(
            "SELECT pedidos.id AS idPedido, pedidos.id_vendedor, pedidos.id_empresa, produtos.nome as nomeproduto, produtos_pedidos.id_produto, pedidos.valor_desconto, pedidos.valor_frete, produtos_pedidos.preco, produtos_pedidos.quantidade, produtos_pedidos.subtotal, clientes.nome AS nomeCliente, clientes.celular as celular,
            IF(pedidos.previsao_entrega = '0000-00-00', 'Não informado', DATE_FORMAT(pedidos.previsao_entrega, '%d/%m/%Y')) AS previsaoEntrega,
            pedidos.valor_frete AS valorFrete, pedidos.data_compensacao, pedidos.id_meio_pagamento, pedidos.id_situacao_pedido, 
            mesas.descricao as mesa,
            CONCAT(clientes_enderecos.endereco , clientes_enderecos.numero, ', ', clientes_enderecos.complemento, ', ',clientes_enderecos.cidade) as endereco,
            pedidos.valor_desconto AS valordesconto,
            situacao.legenda AS situacao,
            pagamento.legenda AS forma_pagamento,
  
            (SELECT SUM(subtotal) FROM produtos_pedidos
              WHERE produtos_pedidos.id_pedido = pedidos.id
            ) + pedidos.valor_frete - pedidos.valor_desconto AS totalGeral
  
            FROM pedidos INNER JOIN clientes ON pedidos.id_cliente = clientes.id
            LEFT JOIN situacoes_pedidos AS situacao ON pedidos.id_situacao_pedido = situacao.id
            LEFT JOIN meios_pagamentos AS pagamento ON pagamento.id = pedidos.id_meio_pagamento
            left join clientes_enderecos on pedidos.id_cliente_endereco = clientes_enderecos.id 
            left join mesas on pedidos.id_mesa = mesas.id
            left join produtos_pedidos on pedidos.id = produtos_pedidos.id_pedido
            left join produtos on produtos_pedidos.id_produto = produtos.id
          WHERE pedidos.id_vendedor = {$idVendedor} {$queryPorCliente} ORDER BY pedidos.id DESC"
        );
    }
}
