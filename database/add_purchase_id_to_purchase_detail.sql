ALTER TABLE `angkortep-purchasing`.`purchase_detail`
    ADD COLUMN `purchase_id` int(11) NULL AFTER `id`;

ALTER TABLE `angkortep-purchasing`.`purchase`
    CHANGE COLUMN `grant_total` `grand_total` decimal(12, 2) NOT NULL AFTER `discount`;

ALTER TABLE `angkortep-purchasing`.`purchase_detail`
    CHANGE COLUMN `color` `color_id` int(11) NULL DEFAULT NULL AFTER `product_id`;
