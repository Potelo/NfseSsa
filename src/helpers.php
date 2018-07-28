<?php

if (!function_exists('xml_view')) {

    /**
     * @param $view
     * @param $data
     * @return string
     * @throws Throwable
     */
    function xml_view($view, $data)
    {
        $view = view("nfse-ssa::$view", $data);

        return $view->render();
    }
}
