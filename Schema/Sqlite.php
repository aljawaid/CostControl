<?php

/**
 * CostControl SQLite schema migrations.
 *
 * Related test: tests/database_migrations.php.
 */

namespace Kanboard\Plugin\CostControl\Schema;

use PDO;

const VERSION = 3;

function version_3(PDO $pdo)
{
    // Store one fixed-scale static cost per task and remove it with its parent task.
    $pdo->exec('CREATE TABLE IF NOT EXISTS cost_control_task_costs (
        task_id INTEGER NOT NULL PRIMARY KEY,
        amount NUMERIC(19,6) NOT NULL,
        FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE
    )');
}

function version_2(PDO $pdo)
{
    // Rebuild plugin-owned tables so existing installations gain fixed-scale declarations safely.
    $pdo->exec('DROP TABLE IF EXISTS cost_control_budget_lines_v2');
    $pdo->exec('CREATE TABLE cost_control_budget_lines_v2 (
        id INTEGER PRIMARY KEY,
        project_id INTEGER NOT NULL,
        amount NUMERIC(19,6) NOT NULL,
        date TEXT NOT NULL,
        comment TEXT,
        FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE
    )');
    $pdo->exec('INSERT INTO cost_control_budget_lines_v2 (id, project_id, amount, date, comment)
        SELECT id, project_id, CAST(amount AS NUMERIC), date, comment FROM budget_lines');
    $pdo->exec('DROP TABLE budget_lines');
    $pdo->exec('ALTER TABLE cost_control_budget_lines_v2 RENAME TO budget_lines');

    $pdo->exec('DROP TABLE IF EXISTS cost_control_hourly_rates_v2');
    $pdo->exec('CREATE TABLE cost_control_hourly_rates_v2 (
        id INTEGER PRIMARY KEY,
        user_id INTEGER NOT NULL,
        rate NUMERIC(19,6) NOT NULL DEFAULT 0,
        date_effective INTEGER NOT NULL,
        currency TEXT NOT NULL,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
    )');
    $pdo->exec('INSERT INTO cost_control_hourly_rates_v2 (id, user_id, rate, date_effective, currency)
        SELECT id, user_id, COALESCE(CAST(rate AS NUMERIC), 0), date_effective, currency FROM hourly_rates');
    $pdo->exec('DROP TABLE hourly_rates');
    $pdo->exec('ALTER TABLE cost_control_hourly_rates_v2 RENAME TO hourly_rates');

    $pdo->exec('CREATE INDEX cost_control_budget_project_date_idx ON budget_lines (project_id, date)');
    $pdo->exec('CREATE INDEX cost_control_hourly_user_date_idx ON hourly_rates (user_id, date_effective)');
}

function version_1(PDO $pdo)
{
    // Create the original budget and hourly-rate data model and extend currencies with rate metadata.
    $pdo->exec('CREATE TABLE IF NOT EXISTS budget_lines (
        id INTEGER PRIMARY KEY,
        project_id INTEGER NOT NULL,
        amount REAL NOT NULL,
        date TEXT NOT NULL,
        comment TEXT,
        FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS hourly_rates (
        id INTEGER PRIMARY KEY,
        user_id INTEGER NOT NULL,
        rate REAL DEFAULT 0,
        date_effective INTEGER NOT NULL,
        currency TEXT NOT NULL,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
    )');

    $pdo->exec('ALTER TABLE currencies ADD COLUMN last_modified INTEGER');
    $pdo->exec('ALTER TABLE currencies ADD COLUMN comment TEXT');
    $pdo->exec('ALTER TABLE currencies ADD COLUMN live_rate DECIMAL(19,6)');
    $pdo->exec('ALTER TABLE currencies ADD COLUMN live_rate_updated INTEGER');
}
