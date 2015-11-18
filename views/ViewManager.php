<?php

class ViewManager
{
    static function render($file,$viewModel = null)
    {
        ob_start();
        require_once $file;
        $renderedView = ob_get_clean();

        return $renderedView;
    }
}