<?php

/**
 * CostControl PostgreSQL schema migrations.
 *
 * Related test: tests/database_migrations.php.
 */

namespace Kanboard\Plugin\CostControl\Schema;

use PDO;

const VERSION = 3;

function version_3(PDO $pdo)
{
    // Preserve financial precision and index the project/user date lookups used by reports.
    $pdo->exec('UPDATE hourly_rates SET rate = 0 WHERE rate IS NULL');
    $pdo->exec('ALTER TABLE budget_lines ALTER COLUMN amount TYPE NUMERIC(19,6) USING amount::numeric');
    $pdo->exec('ALTER TABLE hourly_rates ALTER COLUMN rate TYPE NUMERIC(19,6) USING rate::numeric');
    $pdo->exec('ALTER TABLE hourly_rates ALTER COLUMN rate SET DEFAULT 0');
    $pdo->exec('ALTER TABLE hourly_rates ALTER COLUMN rate SET NOT NULL');
    $pdo->exec('CREATE INDEX IF NOT EXISTS cost_control_budget_project_date_idx ON budget_lines (project_id, date)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS cost_control_hourly_user_date_idx ON hourly_rates (user_id, date_effective)');
}

function version_2(PDO $pdo)
{
    // Replace the original approximate live-rate column with fixed-scale storage.
    $pdo->exec('ALTER TABLE currencies ALTER COLUMN live_rate TYPE NUMERIC(19,6)');
}

function version_1(PDO $pdo)
{
    // Create the original budget and hourly-rate data model and extend currencies with rate metadata.
    $pdo->exec('CREATE TABLE IF NOT EXISTS budget_lines (
        id SERIAL PRIMARY KEY,
        project_id INTEGER NOT NULL,
        amount REAL NOT NULL,
        date VARCHAR(10) NOT NULL,
        comment TEXT,
        FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS hourly_rates (
        id SERIAL PRIMARY KEY,
        user_id INTEGER NOT NULL,
        rate REAL DEFAULT 0,
        date_effective INTEGER NOT NULL,
        currency CHAR(3) NOT NULL,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
    )');

    $pdo->exec("ALTER TABLE currencies ADD COLUMN last_modified INTEGER, ADD COLUMN comment TEXT, ADD COLUMN live_rate REAL DEFAULT 0, ADD COLUMN live_rate_updated INTEGER");
}
