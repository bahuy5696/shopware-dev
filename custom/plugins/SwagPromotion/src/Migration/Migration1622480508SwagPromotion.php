<?php declare(strict_types=1);

namespace SwagPromotion\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1622480508SwagPromotion extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1622480508;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `swag_promotion` (
                `id` BINARY(16) NOT NULL,
                `discount_rate` INT(11) NOT NULL,
                `start_date` DATE NOT NULL,
                `expired_date` DATE NOT NULL,
                `is_active` TINYINT(1) NOT NULL DEFAULT 0,
                `product_version_id` BINARY(16) NULL,
                `product_id` BINARY(16) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                CONSTRAINT `json.swag_promotion.translated` CHECK (JSON_VALID(`translated`)),
                KEY `fk.swag_promotion.product_id` (`product_id`,`product_version_id`),
                CONSTRAINT `fk.swag_promotion.product_id` FOREIGN KEY (`product_id`,`product_version_id`) REFERENCES `product` (`id`,`version_id`) ON DELETE SET NULL ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeUpdate('
            CREATE TABLE `swag_promotion_translation` (
            `name` VARCHAR(255) NOT NULL,
            `created_at` DATETIME(3) NOT NULL,
            `updated_at` DATETIME(3) NULL,
            `swag_promotion_id` BINARY(16) NOT NULL,
            `language_id` BINARY(16) NOT NULL,
            PRIMARY KEY (`swag_promotion_id`,`language_id`),
            CONSTRAINT `fk.swag_promotion_translation.swag_promotion_id` FOREIGN KEY (`swag_promotion_id`) REFERENCES `swag_promotion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `fk.swag_promotion_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

//        $this->updateInheritance($connection, 'product', 'promotions');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
