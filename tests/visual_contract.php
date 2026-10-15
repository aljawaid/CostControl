<?php

/**
 * Verify CostControl navigation icons, theme safety and responsive contracts.
 *
 * Related files: Template/dashboard/layout.php, Template/header/user_dropdown.php,
 * Template/currency/show.php, Assets/css/cost-control.css,
 * Assets/css/cost-control-icons.css and
 * Assets/icons/currency-wallet-icon.svg.
 */

$pluginRoot = dirname(__DIR__);
$navigationTemplates = array(
    $pluginRoot . '/Template/dashboard/layout.php',
    $pluginRoot . '/Template/header/user_dropdown.php',
);
$currencyTemplate = file_get_contents($pluginRoot . '/Template/currency/show.php');
$stylesheet = file_get_contents($pluginRoot . '/Assets/css/cost-control.css');
$iconStylesheet = file_get_contents($pluginRoot . '/Assets/css/cost-control-icons.css');
$walletIcon = file_get_contents($pluginRoot . '/Assets/icons/currency-wallet-icon.svg');
$failures = array();

foreach ($navigationTemplates as $templatePath) {
    $template = file_get_contents($templatePath);

    foreach (
        array(
            'Assets/icons/currency-wallet-icon.svg',
            'class="cost-control-navigation-icon"',
            'width="16" height="16"',
            'alt="" aria-hidden="true"',
            'rel="noopener noreferrer"',
            'tests/visual_contract.php',
        ) as $snippet
    ) {
        if (strpos($template, $snippet) === false) {
            $failures[] = basename($templatePath) . ' is missing the navigation contract: ' . $snippet;
        }
    }

    if (strpos($template, "url->icon('question-circle'") !== false) {
        $failures[] = basename($templatePath) . ' still depends on the Font Awesome placeholder icon.';
    }
}

foreach (
    array(
        'class="currencies-table table-row-hover table-scrolling"',
        'tests/visual_contract.php',
    ) as $snippet
) {
    if (strpos($currencyTemplate, $snippet) === false) {
        $failures[] = 'The currency page is missing the responsive contract: ' . $snippet;
    }
}

foreach (
    array(
        '.cost-control-dropdown .cost-control-navigation-icon',
        'max-width: 100%;',
        'background-color: var(--table-header-background-color, #FFFFFF);',
        '@media (max-width: 768px)',
        '@media (max-width: 480px)',
        'border-left: 5px solid transparent;',
        'tests/visual_contract.php',
    ) as $snippet
) {
    if (strpos($stylesheet, $snippet) === false) {
        $failures[] = 'The stylesheet is missing the visual contract: ' . $snippet;
    }
}

foreach (array('fill:#fff', 'fill:#000', 'fill:maroon') as $color) {
    if (strpos($walletIcon, $color) === false) {
        $failures[] = 'The wallet SVG no longer preserves its established colour: ' . $color;
    }
}

if (strpos($iconStylesheet, 'li.cost-control-dropdown i.fa-question-circle::before') !== false) {
    $failures[] = 'The blocked data-URI navigation override is still present.';
}

if (strpos($iconStylesheet, 'tests/visual_contract.php') === false) {
    $failures[] = 'The icon stylesheet is missing its related visual test reference.';
}

if (strpos($stylesheet, '--link-color-primary:') !== false) {
    $failures[] = 'The plugin still overrides Kanboard global link colours.';
}

if ($failures !== array()) {
    fwrite(STDERR, implode(PHP_EOL, $failures) . PHP_EOL);
    exit(1);
}

echo 'CostControl visual contract passed.' . PHP_EOL;
