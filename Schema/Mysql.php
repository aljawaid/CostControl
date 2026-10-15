<?php

/**
 * CostControl MySQL schema migrations.
 *
 * Related test: tests/database_migrations.php.
 */

namespace Kanboard\Plugin\CostControl\Schema;

use PDO;

const VERSION = 4;

function version_4(PDO $pdo)
{
    // Store one fixed-scale static cost per task and remove it with its parent task.
    $pdo->exec('CREATE TABLE IF NOT EXISTS cost_control_task_costs (
        task_id INT NOT NULL,
        amount DECIMAL(19,6) NOT NULL,
        PRIMARY KEY(task_id),
        FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE
    ) ENGINE=InnoDB CHARSET=utf8mb4');
}

function version_3(PDO $pdo)
{
    // Preserve financial precision and index the project/user date lookups used by reports.
    $pdo->exec('UPDATE hourly_rates SET rate = 0 WHERE rate IS NULL');
    $pdo->exec('ALTER TABLE budget_lines MODIFY amount DECIMAL(19,6) NOT NULL');
    $pdo->exec('ALTER TABLE hourly_rates MODIFY rate DECIMAL(19,6) NOT NULL DEFAULT 0');

    $budgetIndexExists = (int) $pdo->query("SELECT COUNT(*) FROM information_schema.statistics
        WHERE table_schema = DATABASE() AND table_name = 'budget_lines'
        AND index_name = 'cost_control_budget_project_date_idx'")->fetchColumn();
    $hourlyIndexExists = (int) $pdo->query("SELECT COUNT(*) FROM information_schema.statistics
        WHERE table_schema = DATABASE() AND table_name = 'hourly_rates'
        AND index_name = 'cost_control_hourly_user_date_idx'")->fetchColumn();

    if ($budgetIndexExists === 0) {
        $pdo->exec('CREATE INDEX cost_control_budget_project_date_idx ON budget_lines (project_id, date)');
    }

    if ($hourlyIndexExists === 0) {
        $pdo->exec('CREATE INDEX cost_control_hourly_user_date_idx ON hourly_rates (user_id, date_effective)');
    }
}

function version_2(PDO $pdo)
{
    // Replace the original approximate live-rate column with fixed-scale storage.
    $pdo->exec("ALTER TABLE `currencies` MODIFY `live_rate` DECIMAL(19,6)");
}

function version_1(PDO $pdo)
{
    // Create the original budget and hourly-rate data model and extend currencies with rate metadata.
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

    $pdo->exec("ALTER TABLE `currencies` ADD COLUMN `last_modified` INTEGER, ADD COLUMN `comment` TEXT, ADD COLUMN `live_rate` FLOAT DEFAULT 0, ADD COLUMN `live_rate_updated` INTEGER");
}
