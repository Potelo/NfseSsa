<?php

use Illuminate\Support\Str;

if (!function_exists('xml_view')) {

    /**
     * @param $view
     * @param $data
     * @return string
     * @throws Throwable
     */
    function xml_view($view, $data)
    {
        $view = view("nfse-ssa::$view", ['dados' => $data]);

        return $view->render();
    }
}

if (!function_exists('array_get')) {

    /**
     * @param $array
     * @param $key
     * @return null
     */
    function array_get($array, $key)
    {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        return null;
    }
}

if (!function_exists('array_xml_get')) {

    /**
     * @param $array
     * @param $key
     * @return null
     */
    function array_xml_get($array, $key)
    {
        if ($value = array_get($array, $key)) {
            $xmlTag = "<" . Str::studly($key) . ">";
            $xmlCloseTag = "</" . Str::studly($key) . ">";

            return $xmlTag . $value . $xmlCloseTag;
        }

        return null;
    }
}
