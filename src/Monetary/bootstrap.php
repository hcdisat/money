<?php

/**
 * phpunit loader
 */
if( !function_exists('dd') )
{
    function dd($var)
    {
        die(var_dump($var));
    }
}

if( !function_exists('config') )
{
    function config($path)
    {
        $items = [
            'default-locale' => 'en_US',
            'formatter-format' => '#,##0.00, -#,##0.00',
        ];

        $array_path = explode('.', $path);

        return $items[$array_path[1]];
    }
}