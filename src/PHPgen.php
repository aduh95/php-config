<?php

namespace aduh95\PHPConfig;

/**
 * @author aduh95
 * @license MIT
 * Generates the PHP script
 */
class PHPgen
{
    /**
     * Parse one or two INI files to output the PHP code to define the corresponding constants
     *
     * @param string $defaultValues The path to the default values INI file
     * @param string $specificValues Idem
     * @param string $defaultNamespace The namespace used if not specified in the default file
     *
     * @return string The PHP script to define the corresponding constants
     */
    public static function parseINI($defaultValues, $specificValues = '', $defaultNamespace = 'CONFIG')
    {
        $return = '';
        $default = parse_ini_file($defaultValues, true, INI_SCANNER_TYPED);
        $specific = is_readable($specificValues) ? parse_ini_file($specificValues, true) : array();
        $namespace = $defaultNamespace;

        foreach ($default as $section => $values) {
            if (!is_array($values)) {
                if ($section === 'namespace') {
                    $namespace = $values . '\\';
                }
                continue;
            } elseif (!isset($specific[$section]) || !is_array($specific['section'])) {
                $specific[$section] = array();
            }

            $return.= PHP_EOL.PHP_EOL.'namespace '.$namespace.$section.';'.PHP_EOL.PHP_EOL;

            foreach ($values as $key => $value) {
                if (isset($specific[$section]) && isset($specific[$section][$key])) {
                    $value = $specific[$section][$key];
                }

                $return.= 'const '.$key.' = '.self::getPHPRepresentation($value, $values).';'.PHP_EOL;
            }
        }

        return $return;
    }

    /**
     * Returns the PHP representation for a given value
     *
     * @param mixed $value
     * @param array $alreadyDefined The already defined values to support concatenation
     *
     * @return string
     */
    public static function getPHPRepresentation($value, $alreadyDefined = array())
    {
        if (is_numeric($value)) {
            $return = intval($value);
        } elseif (is_bool($value)) {
            $return = $value ? 'true' : 'false';
        } elseif (is_array($value)) {
            $return = 'array('.PHP_EOL;
            foreach ($value as $key => $val) {
                $return.= "\t".self::getPHPRepresentation($key).' => '.self::getPHPRepresentation($val).','.PHP_EOL;
            }
            $return = $return.')';
        } elseif (preg_match('#<\?.+\?>#', $value)) {
            $return = substr($value, 2, -2);
        } elseif (preg_match('#^[A-Za-z0-9_]+(\+[A-Za-z0-9_]+)+$#', $value) &&
            array_reduce(explode('+', $value), function ($pv, $key) use ($alreadyDefined) {
                return $pv && (isset($alreadyDefined[$key]) || defined($key));
            }, true)) {
            $return = str_replace('+', '.', $value);
        } else {
            $value = str_replace("'", "\\'", $value);
            $return = "'$value'";
        }

        return $return;
    }

}
