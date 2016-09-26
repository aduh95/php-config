<?php
/**
 * Checks if the compliled PHP is up to date
 * @author aduh95
 * @license MIT
 */

if (is_readable(CONFIG\OPTIMISED_CONFIG)) {
    require_once OPTIMISED_CONFIG;
    return;
}
