ALTER TABLE `oc_emailtemplate_logs` ADD `emailtemplate_key` varchar(32) NULL DEFAULT NULL AFTER `emailtemplate_id`;
ALTER TABLE `oc_emailtemplate_logs` ADD `customer_group_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `customer_id`;