
ALTER TABLE `support_loan`.`sales` ENGINE = InnoDB;
ALTER TABLE `support_loan`.`user_groups` ENGINE = InnoDB;
ALTER TABLE `support_loan`.`transactions` ENGINE = InnoDB;
ALTER TABLE `support_loan`.`timline_details` ENGINE = InnoDB;
ALTER TABLE `support_loan`.`timelines` ENGINE = InnoDB;
ALTER TABLE `support_loan`.`siteprofiles` ENGINE = InnoDB;
ALTER TABLE `support_loan`.`salesmen` ENGINE = InnoDB;
ALTER TABLE `support_loan`.`permissions` ENGINE = InnoDB;
ALTER TABLE `support_loan`.`payments` ENGINE = InnoDB;
ALTER TABLE `support_loan`.`password_resets` ENGINE = InnoDB;
ALTER TABLE `support_loan`.`migrations` ENGINE = InnoDB;
ALTER TABLE `support_loan`.`loans` ENGINE = InnoDB;
ALTER TABLE `support_loan`.`journals` ENGINE = InnoDB;
ALTER TABLE `support_loan`.`investments` ENGINE = InnoDB;
ALTER TABLE `support_loan`.`incomes` ENGINE = InnoDB;
ALTER TABLE `support_loan`.`guarantors` ENGINE = InnoDB;
ALTER TABLE `support_loan`.`group_incomes` ENGINE = InnoDB;
ALTER TABLE `support_loan`.`Dealers` ENGINE = InnoDB;
ALTER TABLE `support_loan`.`Branches` ENGINE = InnoDB;
ALTER TABLE `support_loan`.`branch_dealers` ENGINE = InnoDB;
ALTER TABLE `support_loan`.`banks` ENGINE = InnoDB;

ALTER TABLE `sales` CHANGE `price` `price` DECIMAL(20,2) NULL DEFAULT NULL, CHANGE `deposit` `deposit` DECIMAL(20,2) NULL DEFAULT NULL, CHANGE `total` `total` DECIMAL(20,2) NULL DEFAULT NULL, CHANGE `interest` `interest` DECIMAL(20,2) NULL DEFAULT NULL;
ALTER TABLE `transactions` CHANGE `amount_usd` `amount_usd` DECIMAL(20,2) NULL DEFAULT NULL, CHANGE `amount_riel` `amount_riel` DECIMAL(20,2) NULL DEFAULT NULL, CHANGE `interest_usd` `interest_usd` DECIMAL(20,2) NULL DEFAULT NULL, CHANGE `interest_riel` `interest_riel` DECIMAL(20,2) NULL DEFAULT NULL, CHANGE `exchange` `exchange` DECIMAL(20,2) NULL DEFAULT NULL;
ALTER TABLE `payments` CHANGE `pay_gap` `pay_gap` DECIMAL(20,2) NULL DEFAULT NULL, CHANGE `amount` `amount` DECIMAL(20,2) NULL DEFAULT NULL, CHANGE `t_amount` `t_amount` DECIMAL(20,2) NULL DEFAULT NULL, CHANGE `interest` `interest` DECIMAL(20,2) NULL DEFAULT NULL, CHANGE `t_interest` `t_interest` DECIMAL(20,2) NULL DEFAULT NULL, CHANGE `total` `total` DECIMAL(20,2) NULL DEFAULT NULL, CHANGE `balance` `balance` DECIMAL(20,2) NULL DEFAULT NULL, CHANGE `paid_off_interest` `paid_off_interest` DECIMAL(20,2) NULL DEFAULT NULL, CHANGE `paid_off_capital` `paid_off_capital` DECIMAL(20,2) NULL DEFAULT NULL, CHANGE `total_paid_off` `total_paid_off` DECIMAL(20,2) NULL DEFAULT NULL;
ALTER TABLE `users` CHANGE `group_id` `group_id` INT NULL, CHANGE `branch_id` `branch_id` INT NULL;
ALTER TABLE `customers` CHANGE `identitycard_number_date` `identitycard_number_date` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `payments` ADD `total_paid_amount_ riel` DOUBLE(20,2) NOT NULL AFTER `total`, ADD `total_paid_amount_usd` DOUBLE(20,2) NOT NULL AFTER `total_paid_amount_ riel`;
ALTER TABLE `payments` CHANGE `total_paid_amount_ riel` `total_paid_amount_ riel` DOUBLE(20,2) NULL, CHANGE `total_paid_amount_usd` `total_paid_amount_usd` DOUBLE(20,2) NULL;
ALTER TABLE `payments` CHANGE `total_paid_amount_ riel` `total_paid_amount_riel` DOUBLE(20,2) NULL DEFAULT NULL;
-- =========CHANGE
ALTER TABLE `payment_transactions` 
ADD COLUMN `loan_id` int(11) NULL AFTER `id`,
MODIFY COLUMN `payment_id` int(11) NULL AFTER `id`;
-- ===========
ALTER TABLE `sales` 
MODIFY COLUMN `approve_status` enum('pending','approved','reject','payoff') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'pending' AFTER `status`;