<?php
/**
 * Created by PhpStorm.
 * User: yunlong
 * Date: 14-7-25
 * Time: 下午7:22
 */


if ( ! function_exists('string_to_html_a'))
{
    function string_to_html_a($url, $text,$attribute='')
    {
        $htmlStr = "<a href=$url $attribute>$text</a>";
        return $htmlStr;
    }
}


