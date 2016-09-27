<?php
/**
 * Handle the interaction with the user
 * @author aduh95
 * @license MIT
 */

namespace aduh95\PHPConfig;

/**
*
*/
class PHPconfig
{
    public static function init()
    {
        exit('Not ready yet!');
    }

    public static function recompile()
    {
        $config = (array)json_decode(file_get_contents(CONFIG\SETTINGS_CACHE), true);

        $f = fopen(CONFIG\COMPILED_CONFIG, 'w');
        fwrite($f, file_get_contents(CONFIG\PHP_MODEL));
        fwrite($f, PHPgen::parseINI($config['default'], $config['specific']));
        fclose($f);

        return true;
    }
}
