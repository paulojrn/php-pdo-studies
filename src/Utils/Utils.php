<?php

namespace Alura\Pdo\Utils;

class Utils
{
    public static function strCamelCaseToSnakeCase(string $str): string
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $str)));
        $str[0] = strtolower($str[0]);

        return $str;
    }

    public static function strSnakeCaseToCamelCase(string $str): string
    {
        return ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $str)), '_');
    }
}
