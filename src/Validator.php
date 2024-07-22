<?php

namespace App;

class Validator
{
    public function isXSS($input)
    {
        // Enhanced XSS check (for demonstration purposes)
        $xssPattern = '/(<script\b[^>]*>(.*?)<\/script>|<[^>]+(on\w*|javascript:|vbscript:|data:)[^>]*>)/i';
        return preg_match($xssPattern, $input) === 1;
    }

    public function isSQLInjection($input)
    {
        // Simple SQL Injection check (for demonstration purposes)
        $blacklist = ['SELECT', 'INSERT', 'UPDATE', 'DELETE', 'DROP', 'UNION', '--', '#', '/*', '*/'];
        foreach ($blacklist as $sql) {
            if (stripos($input, $sql) !== false) {
                return true;
            }
        }
        return false;
    }
}