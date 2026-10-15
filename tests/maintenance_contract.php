<?php

/**
 * Verify CostControl runtime modernisation, compatibility and maintenance contracts.
 *
 * Related files: Plugin.php, Assets/js/BudgetChart.js,
 * Assets/js/cost-control.js, Assets/js/cost-control-top-btn.js,
 * README.md, .github/workflows/linter.yml and
 * .github/linters/eslint.config.mjs.
 */

$pluginRoot = dirname(__DIR__);
$plugin = file_get_contents($pluginRoot . '/Plugin.php');
$budgetChart = file_get_contents($pluginRoot . '/Assets/js/BudgetChart.js');
$currencyScript = file_get_contents($pluginRoot . '/Assets/js/cost-control.js');
$scrollScript = file_get_contents($pluginRoot . '/Assets/js/cost-control-top-btn.js');
$readme = file_get_contents($pluginRoot . '/README.md');
$linterWorkflow = file_get_contents($pluginRoot . '/.github/workflows/linter.yml');
$eslintConfig = file_get_contents($pluginRoot . '/.github/linters/eslint.config.mjs');
$failures = array();

foreach (
    array(
        'Assets/js/BudgetChart.js' => $budgetChart,
        'Assets/js/cost-control.js' => $currencyScript,
        'Assets/js/cost-control-top-btn.js' => $scrollScript,
    ) as $file => $script
) {
    foreach (array('jQuery(', '$(', 'var ') as $legacySyntax) {
        if (strpos($script, $legacySyntax) !== false) {
            $failures[] = $file . ' still contains legacy syntax: ' . $legacySyntax;
        }
    }

    if (strpos($script, 'tests/maintenance_contract.php') === false) {
        $failures[] = $file . ' is missing its related maintenance test reference.';
    }
}

foreach (
    array(
        'c3.generate(',
        'd3.time.format(',
        'JSON.parse(chart.dataset.metrics)',
        'bindto: "#chart"',
    ) as $snippet
) {
    if (strpos($budgetChart, $snippet) === false) {
        $failures[] = 'The budget chart no longer preserves its rendering contract: ' . $snippet;
    }
}

foreach (
    array(
        'input.addEventListener("input"',
        'text.includes(value)',
        'row.hidden = !text.includes(value)',
        'input.form.addEventListener("submit"',
        'window.alert("The enter key is ignored as it is not required in this filter")',
    ) as $snippet
) {
    if (strpos($currencyScript, $snippet) === false) {
        $failures[] = 'The currency filter no longer preserves its interaction contract: ' . $snippet;
    }
}

foreach (
    array(
        'window.matchMedia("(prefers-reduced-motion: reduce)")',
        'window.scrollTo({',
        'target.getBoundingClientRect().top + window.scrollY - 100',
    ) as $snippet
) {
    if (strpos($scrollScript, $snippet) === false) {
        $failures[] = 'The anchor controls no longer preserve their scrolling contract: ' . $snippet;
    }
}

foreach (
    array(
        "is_dir(PLUGINS_DIR . DIRECTORY_SEPARATOR . 'PluginManager')",
        "CostControlLayoutHelper::class",
        "return '>=1.2.54';",
        'tests/maintenance_contract.php',
    ) as $snippet
) {
    if (strpos($plugin, $snippet) === false) {
        $failures[] = 'Plugin.php is missing the maintenance contract: ' . $snippet;
    }
}

foreach (
    array(
        'use Kanboard\\Model\\CurrencyModel;',
        'use Kanboard\\Helper;',
        "file_exists('plugins/PluginManager')",
    ) as $legacySnippet
) {
    if (strpos($plugin, $legacySnippet) !== false) {
        $failures[] = 'Plugin.php still contains obsolete code: ' . $legacySnippet;
    }
}

foreach (
    array(
        'Requires [Kanboard](https://github.com/kanboard/kanboard "Kanboard - Kanban Project Management Software") ≥`1.2.54`',
        'Tested with PHP `8.2`–`8.5`',
        '[@aljawaid](https://github.com/aljawaid) - Author',
        '[Craig Crosby](https://github.com/creecros) - Contributor',
        '[Chaosmeister](https://github.com/chaosmeister) - Contributor',
        '[Frédéric Guillot](https://github.com/kanboard/plugin-budget) - Author',
        'tests/maintenance_contract.php',
    ) as $snippet
) {
    if (strpos($readme, $snippet) === false) {
        $failures[] = 'README.md is missing the maintenance contract: ' . $snippet;
    }
}

preg_match_all('/<a\\b[^>]*href="https?:\\/\\/[^\"]+"[^>]*>/i', $readme, $externalAnchors);
foreach ($externalAnchors[0] as $anchor) {
    if (strpos($anchor, 'rel="noopener noreferrer"') === false) {
        $failures[] = 'README.md contains an external HTML link without privacy protections: ' . $anchor;
    }
}

foreach (
    array(
        'super-linter/super-linter/slim@2da136927bd4a73596db63044b504547c62cb854',
        'PHP_PHPCS_FILE_NAME: phpcs.xml',
        'JAVASCRIPT_ES_CONFIG_FILE: eslint.config.mjs',
        'SAVE_SUPER_LINTER_SUMMARY: true',
        'tests/maintenance_contract.php',
    ) as $snippet
) {
    if (strpos($linterWorkflow, $snippet) === false) {
        $failures[] = 'The linter workflow is missing the maintenance contract: ' . $snippet;
    }
}

if (preg_match('/^\\s+VALIDATE_(?!ALL_CODEBASE)[A-Z0-9_]+:\s+false\\s*$/m', $linterWorkflow) === 1) {
    $failures[] = 'The linter workflow mixes included and excluded validators.';
}

if (strpos($linterWorkflow, 'VALIDATE_PHP:') !== false) {
    $failures[] = 'The linter workflow still uses the legacy PHP validator alias.';
}

foreach (
    array(
        'files: ["Assets/js/**/*.js"]',
        '"no-undef": "error"',
        '"no-var": "error"',
        'tests/maintenance_contract.php',
    ) as $snippet
) {
    if (strpos($eslintConfig, $snippet) === false) {
        $failures[] = 'The ESLint flat configuration is missing the maintenance contract: ' . $snippet;
    }
}

if ($failures !== array()) {
    fwrite(STDERR, implode(PHP_EOL, $failures) . PHP_EOL);
    exit(1);
}

echo 'CostControl maintenance contract passed.' . PHP_EOL;
