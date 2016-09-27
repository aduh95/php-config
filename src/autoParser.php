<?php
/**
 * Checks if the compliled PHP is up to date
 * @author aduh95
 * @license MIT
 */

namespace aduh95\PHPConfig;

require __DIR__.'/../bin/cache/config.php';
if (is_readable(CONFIG\OPTIMISED_CONFIG)) {
    require_once CONFIG\OPTIMISED_CONFIG;
    return;
} elseif (!is_readable(CONFIG\SETTINGS_CACHE)) {
    PHPconfig::init();
} else {
    PHPconfig::recompile();
}
