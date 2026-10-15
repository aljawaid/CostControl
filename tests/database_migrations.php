<?php

/**
 * Verify fresh and upgrade CostControl schemas on SQLite, MySQL and PostgreSQL.
 *
 * Related files: Schema/Sqlite.php, Schema/Mysql.php and Schema/Postgres.php.
 */

if ($argc !== 2 || !in_array($argv[1], array('sqlite', 'mysql', 'postgres'), true)) {
    fwrite(STDERR, 'Usage: php tests/database_migrations.php sqlite|mysql|postgres' . PHP_EOL);
    exit(2);
}

$driver = $argv[1];
$failures = array();

function assertMigration($condition, $message)
{
    global $failures;

    if (!$condition) {
        $failures[] = $message;
    }
}

function connectDatabase($driver)
{
    if ($driver === 'sqlite') {
        $pdo = new PDO('sqlite::memory:');
        $pdo->exec('PRAGMA foreign_keys = ON');
    } else {
        $dsn = getenv('COSTCONTROL_TEST_DSN');
        $user = getenv('COSTCONTROL_TEST_USER');
        $password = getenv('COSTCONTROL_TEST_PASSWORD');

        if ($dsn === false || $user === false || $password === false) {
            throw new RuntimeException('Database connection environment variables are required.');
        }

        $pdo = new PDO($dsn, $user, $password);
    }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

function resetDatabase(PDO $pdo, $driver)
{
    if ($driver === 'mysql') {
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    }

    foreach (array('cost_control_task_costs', 'hourly_rates', 'budget_lines', 'tasks', 'currencies', 'users', 'projects') as $table) {
        $suffix = $driver === 'postgres' ? ' CASCADE' : '';
        $pdo->exec('DROP TABLE IF EXISTS ' . $table . $suffix);
    }

    if ($driver === 'mysql') {
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    }
}

function createCoreTables(PDO $pdo, $driver)
{
    if ($driver === 'mysql') {
        $pdo->exec('CREATE TABLE projects (id INT NOT NULL PRIMARY KEY) ENGINE=InnoDB');
        $pdo->exec('CREATE TABLE users (id INT NOT NULL PRIMARY KEY) ENGINE=InnoDB');
        $pdo->exec('CREATE TABLE currencies (currency CHAR(3) NOT NULL PRIMARY KEY, rate FLOAT DEFAULT 0) ENGINE=InnoDB');
        $pdo->exec('CREATE TABLE tasks (id INT NOT NULL PRIMARY KEY, project_id INT NOT NULL, is_active TINYINT NOT NULL DEFAULT 1,
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE) ENGINE=InnoDB');
    } elseif ($driver === 'postgres') {
        $pdo->exec('CREATE TABLE projects (id INTEGER PRIMARY KEY)');
        $pdo->exec('CREATE TABLE users (id INTEGER PRIMARY KEY)');
        $pdo->exec('CREATE TABLE currencies (currency CHAR(3) NOT NULL PRIMARY KEY, rate REAL DEFAULT 0)');
        $pdo->exec('CREATE TABLE tasks (id INTEGER PRIMARY KEY, project_id INTEGER NOT NULL, is_active BOOLEAN NOT NULL DEFAULT TRUE,
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE)');
    } else {
        $pdo->exec('CREATE TABLE projects (id INTEGER PRIMARY KEY)');
        $pdo->exec('CREATE TABLE users (id INTEGER PRIMARY KEY)');
        $pdo->exec('CREATE TABLE currencies (currency TEXT NOT NULL PRIMARY KEY, rate REAL DEFAULT 0)');
        $pdo->exec('CREATE TABLE tasks (id INTEGER PRIMARY KEY, project_id INTEGER NOT NULL, is_active INTEGER NOT NULL DEFAULT 1,
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE)');
    }

    $pdo->exec('INSERT INTO projects (id) VALUES (1)');
    $pdo->exec('INSERT INTO users (id) VALUES (1)');
    $pdo->exec('INSERT INTO tasks (id, project_id) VALUES (1, 1)');
    $pdo->exec("INSERT INTO currencies (currency, rate) VALUES ('USD', 1)");
}

function runSchemaVersions($namespace, $firstVersion, $lastVersion, PDO $pdo)
{
    for ($version = $firstVersion; $version <= $lastVersion; $version++) {
        $migration = $namespace . 'version_' . $version;

        if (function_exists($migration)) {
            $migration($pdo);
        }
    }
}

function getColumnMetadata(PDO $pdo, $driver, $table, $column)
{
    if ($driver === 'mysql') {
        $statement = $pdo->prepare('SELECT DATA_TYPE AS type, NUMERIC_PRECISION AS precision_value,
            NUMERIC_SCALE AS scale_value, IS_NULLABLE AS nullable_value
            FROM information_schema.columns
            WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?');
        $statement->execute(array($table, $column));
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    if ($driver === 'postgres') {
        $statement = $pdo->prepare('SELECT data_type AS type, numeric_precision AS precision_value,
            numeric_scale AS scale_value, is_nullable AS nullable_value
            FROM information_schema.columns
            WHERE table_schema = current_schema() AND table_name = ? AND column_name = ?');
        $statement->execute(array($table, $column));
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    foreach ($pdo->query('PRAGMA table_info(' . $table . ')')->fetchAll(PDO::FETCH_ASSOC) as $metadata) {
        if ($metadata['name'] === $column) {
            return array(
                'type' => strtoupper($metadata['type']),
                'precision_value' => 19,
                'scale_value' => 6,
                'nullable_value' => (int) $metadata['notnull'] === 1 ? 'NO' : 'YES',
            );
        }
    }

    return false;
}

function hasIndex(PDO $pdo, $driver, $index)
{
    if ($driver === 'mysql') {
        $statement = $pdo->prepare('SELECT COUNT(*) FROM information_schema.statistics
            WHERE table_schema = DATABASE() AND index_name = ?');
    } elseif ($driver === 'postgres') {
        $statement = $pdo->prepare('SELECT COUNT(*) FROM pg_indexes
            WHERE schemaname = current_schema() AND indexname = ?');
    } else {
        $statement = $pdo->prepare("SELECT COUNT(*) FROM sqlite_master WHERE type = 'index' AND name = ?");
    }

    $statement->execute(array($index));
    return (int) $statement->fetchColumn() > 0;
}

function verifyCurrentSchema(PDO $pdo, $driver, $scenario)
{
    $amount = getColumnMetadata($pdo, $driver, 'budget_lines', 'amount');
    $rate = getColumnMetadata($pdo, $driver, 'hourly_rates', 'rate');
    $taskCost = getColumnMetadata($pdo, $driver, 'cost_control_task_costs', 'amount');
    $expectedType = $driver === 'mysql' ? 'decimal' : ($driver === 'postgres' ? 'numeric' : 'NUMERIC(19,6)');

    assertMigration($amount !== false && $amount['type'] === $expectedType, $scenario . ': budget amount type is not fixed-scale.');
    assertMigration($rate !== false && $rate['type'] === $expectedType, $scenario . ': hourly rate type is not fixed-scale.');
    assertMigration($taskCost !== false && $taskCost['type'] === $expectedType, $scenario . ': static task cost type is not fixed-scale.');
    assertMigration((int) $amount['precision_value'] === 19 && (int) $amount['scale_value'] === 6, $scenario . ': budget amount precision is incorrect.');
    assertMigration((int) $rate['precision_value'] === 19 && (int) $rate['scale_value'] === 6, $scenario . ': hourly rate precision is incorrect.');
    assertMigration((int) $taskCost['precision_value'] === 19 && (int) $taskCost['scale_value'] === 6, $scenario . ': static task cost precision is incorrect.');
    assertMigration($amount['nullable_value'] === 'NO', $scenario . ': budget amount permits null values.');
    assertMigration($rate['nullable_value'] === 'NO', $scenario . ': hourly rate permits null values.');
    assertMigration($taskCost['nullable_value'] === 'NO', $scenario . ': static task cost permits null values.');
    assertMigration(hasIndex($pdo, $driver, 'cost_control_budget_project_date_idx'), $scenario . ': budget lookup index is missing.');
    assertMigration(hasIndex($pdo, $driver, 'cost_control_hourly_user_date_idx'), $scenario . ': hourly-rate lookup index is missing.');
}

try {
    $pdo = connectDatabase($driver);
    $schemaName = $driver === 'postgres' ? 'Postgres' : ucfirst($driver);
    $schemaFile = dirname(__DIR__) . '/Schema/' . $schemaName . '.php';
    $namespace = 'Kanboard\\Plugin\\CostControl\\Schema\\';

    require $schemaFile;
    $latestVersion = constant($namespace . 'VERSION');

    resetDatabase($pdo, $driver);
    createCoreTables($pdo, $driver);
    runSchemaVersions($namespace, 1, $latestVersion, $pdo);
    verifyCurrentSchema($pdo, $driver, 'Fresh install');

    $pdo->exec("INSERT INTO budget_lines (id, project_id, amount, date, comment)
        VALUES (11, 1, 12345.123456, '2026-10-15', 'fresh')");
    $pdo->exec("INSERT INTO hourly_rates (id, user_id, rate, date_effective, currency)
        VALUES (12, 1, 98.765432, 1792022400, 'GBP')");
    $pdo->exec('INSERT INTO cost_control_task_costs (task_id, amount)
        VALUES (1, 456.123456)');
    assertMigration(number_format((float) $pdo->query('SELECT amount FROM budget_lines WHERE id = 11')->fetchColumn(), 6, '.', '') === '12345.123456', 'Fresh install: budget precision did not round-trip.');
    assertMigration(number_format((float) $pdo->query('SELECT rate FROM hourly_rates WHERE id = 12')->fetchColumn(), 6, '.', '') === '98.765432', 'Fresh install: hourly-rate precision did not round-trip.');
    assertMigration(number_format((float) $pdo->query('SELECT amount FROM cost_control_task_costs WHERE task_id = 1')->fetchColumn(), 6, '.', '') === '456.123456', 'Fresh install: static task cost precision did not round-trip.');
    $pdo->exec('DELETE FROM tasks WHERE id = 1');
    assertMigration((int) $pdo->query('SELECT COUNT(*) FROM cost_control_task_costs')->fetchColumn() === 0, 'Fresh install: static task cost did not cascade with its task.');

    resetDatabase($pdo, $driver);
    createCoreTables($pdo, $driver);
    // Reproduce an upgrade from the original schema so every historical repair remains exercised.
    runSchemaVersions($namespace, 1, 1, $pdo);
    $pdo->exec("INSERT INTO budget_lines (id, project_id, amount, date, comment)
        VALUES (41, 1, 12345.123456, '2026-10-14', 'preserve me')");
    $pdo->exec("INSERT INTO hourly_rates (id, user_id, rate, date_effective, currency)
        VALUES (42, 1, 98.765432, 1791936000, 'EUR')");
    $pdo->exec("INSERT INTO hourly_rates (id, user_id, rate, date_effective, currency)
        VALUES (43, 1, NULL, 1792022400, 'GBP')");
    // Legacy FLOAT and REAL columns may already have rounded the input; preserve the stored value.
    $legacyBudgetAmount = number_format(
        (float) $pdo->query('SELECT amount FROM budget_lines WHERE id = 41')->fetchColumn(),
        6,
        '.',
        ''
    );
    $legacyHourlyRate = number_format(
        (float) $pdo->query('SELECT rate FROM hourly_rates WHERE id = 42')->fetchColumn(),
        6,
        '.',
        ''
    );
    runSchemaVersions($namespace, 2, $latestVersion, $pdo);
    // A failed MySQL DDL transaction can be retried before Kanboard records the new schema version.
    runSchemaVersions($namespace, $latestVersion, $latestVersion, $pdo);
    verifyCurrentSchema($pdo, $driver, 'Upgrade');
    $pdo->exec('INSERT INTO cost_control_task_costs (task_id, amount)
        VALUES (1, 321.654321)');

    $budget = $pdo->query('SELECT * FROM budget_lines WHERE id = 41')->fetch(PDO::FETCH_ASSOC);
    $hourlyRate = $pdo->query('SELECT * FROM hourly_rates WHERE id = 42')->fetch(PDO::FETCH_ASSOC);
    assertMigration((int) $budget['project_id'] === 1 && $budget['comment'] === 'preserve me' && $budget['date'] === '2026-10-14', 'Upgrade: budget row metadata was not preserved.');
    assertMigration(number_format((float) $budget['amount'], 6, '.', '') === $legacyBudgetAmount, 'Upgrade: budget amount was not preserved.');
    assertMigration((int) $hourlyRate['user_id'] === 1 && trim($hourlyRate['currency']) === 'EUR', 'Upgrade: hourly-rate row metadata was not preserved.');
    assertMigration(number_format((float) $hourlyRate['rate'], 6, '.', '') === $legacyHourlyRate, 'Upgrade: hourly rate was not preserved.');
    assertMigration((float) $pdo->query('SELECT rate FROM hourly_rates WHERE id = 43')->fetchColumn() === 0.0, 'Upgrade: null hourly rate was not repaired.');
    assertMigration(number_format((float) $pdo->query('SELECT amount FROM cost_control_task_costs WHERE task_id = 1')->fetchColumn(), 6, '.', '') === '321.654321', 'Upgrade: static task cost precision did not round-trip.');

    resetDatabase($pdo, $driver);
} catch (Throwable $exception) {
    $failures[] = $driver . ': ' . $exception->getMessage();
}

if ($failures !== array()) {
    fwrite(STDERR, implode(PHP_EOL, $failures) . PHP_EOL);
    exit(1);
}

echo 'CostControl ' . $driver . ' fresh and upgrade migrations passed.' . PHP_EOL;
