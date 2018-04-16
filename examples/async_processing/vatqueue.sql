DROP TABLE IF EXISTS `vat_validation`;
CREATE TABLE `vat_validation` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, 
    `vat_id` TEXT NOT NULL, 
    `validated` TEXT NOT NULL DEFAULT '', 
    `result` TEXT NOT NULL DEFAULT '', 
    `reason` TEXT NOT NULL DEFAULT '', 
    `name` TEXT NOT NULL DEFAULT '', 
    `address` TEXT NOT NULL DEFAULT '', 
    `identifier` TEXT NOT NULL DEFAULT ''
);
