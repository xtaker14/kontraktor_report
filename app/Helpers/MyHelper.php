<?php

namespace app\Helpers;

class MyHelper {

    /**
     * fungsi untuk replace karakter dengan symbol
     * fungsi default replace spasi dengan underscore
     */
    public static function replaceWithSymbol($input, $replace=' ', $symbol = '_')
    {
        return str_replace($replace, $symbol, $input);
    }
    
    /**
     * fungsi untuk replace symbol menjadi space
     * @param type $input
     */
    public static function removeSymbol($input)
    {
        $pattern = '/([\W]+)/';
        $replace = ' ';
        return preg_replace($pattern, $replace, $input);
    }
}