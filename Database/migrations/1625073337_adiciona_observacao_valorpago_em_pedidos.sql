ALTER TABLE `pedidos`
	ADD COLUMN `observacao_pedido` varchar(100) NULL DEFAULT NULL AFTER `id_mesa`;

ALTER TABLE `pedidos`
	ADD COLUMN `valor_troco` double DEFAULT '0' AFTER `valor_frete`;
