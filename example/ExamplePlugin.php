<?php

namespace Potherca\Examples\Composer\Plugins;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
class ExamplePlugin extends \Potherca\Composer\Plugins\BasePlugin
{
    final public function __call($p_sMethodName, $p_aArguments)
    {
        echo '--- CALLED: ' . __CLASS__ . '::' . $p_sMethodName . PHP_EOL;
        echo '    ARGUMENTS: ('. count($p_aArguments) .')' .PHP_EOL;
        foreach ($p_aArguments as $t_iIndex => $t_uArgument) {
            if (is_object($t_uArgument)) {
                echo '    ' . ($t_iIndex+1) . '. ' . get_class($t_uArgument) . PHP_EOL;
            } else {
                echo '    ' . ($t_iIndex+1) . '. ' . var_export($t_uArgument,true) . PHP_EOL;
            }
        }
    }
}
