<?php

namespace Kanboard\Plugin\CostControl\Schema;

use PDO;

const VERSION = 2;

function version_2(PDO $pdo)
{
    $pdo->exec("ALTER TABLE `currencies` MODIFY `live_rate` DECIMAL(19,6)");
}

function version_1(PDO $pdo)
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS budget_lines (
        `id` INT NOT NULL AUTO_INCREMENT,
        `project_id` INT NOT NULL,
        `amount` FLOAT NOT NULL,
        `date` VARCHAR(10) NOT NULL,
        `comment` TEXT,
        FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE,
        PRIMARY KEY(id)
    ) ENGINE=InnoDB CHARSET=utf8');

    $pdo->exec("CREATE TABLE IF NOT EXISTS hourly_rates (
        id INT NOT NULL AUTO_INCREMENT,
        user_id INT NOT NULL,
        rate FLOAT DEFAULT 0,
        date_effective INTEGER NOT NULL,
        currency CHAR(3) NOT NULL,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
        PRIMARY KEY(id)
    ) ENGINE=InnoDB CHARSET=utf8");

    /* IF THE PLUGIN IS NOT INSTALLING, DELETE IF THIS COLUMN EXISTS */
    $pdo->exec("ALTER TABLE `currencies` ADD COLUMN `last_modified` INTEGER, ADD COLUMN `comment` TEXT, ADD COLUMN `live_rate` FLOAT DEFAULT 0, ADD COLUMN `live_rate_updated` INTEGER");
}
