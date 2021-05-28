ALTER TABLE `vendas`
	ADD COLUMN `deleted_at` TIMESTAMP NULL DEFAULT NULL AFTER `updated_at`;

ALTER TABLE `vendas`
	ADD COLUMN `id_pedido` INT(255) DEFAULT NULL AFTER `id_empresa`;
