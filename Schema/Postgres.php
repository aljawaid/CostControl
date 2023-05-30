<?php

namespace Kanboard\Plugin\CostControl\Schema;

use PDO;

const VERSION = 2;

function version_2(PDO $pdo)
{
    $pdo->exec('ALTER TABLE currencies ALTER COLUMN live_rate TYPE NUMERIC(19,6)');
}

function version_1(PDO $pdo)
{
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

    /* IF THE PLUGIN IS NOT INSTALLING, DELETE IF THIS COLUMN EXISTS */
    $pdo->exec("ALTER TABLE currencies ADD COLUMN last_modified INTEGER, ADD COLUMN comment TEXT, ADD COLUMN live_rate REAL DEFAULT 0, ADD COLUMN live_rate_updated INTEGER");
}
