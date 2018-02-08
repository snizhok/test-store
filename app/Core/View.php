<?php

namespace App\Core;

class View
{
    public static function render(string $view, array $params = [])
    {
        if (!file_exists(__DIR__ . '/../../resources/views/layout.php')) {
            throw new \Exception('Layout ' . $view . ' does not exists');
        }
        if (!file_exists(__DIR__ . '/../../resources/views/' . str_replace('.', '/', $view) . '.php')) {
            throw new \Exception('View ' . $view . ' does not exists');
        }

        extract($params);

        ob_start();
        include __DIR__ . '/../../resources/views/' . str_replace('.', '/', $view) . '.php';
        $content = ob_get_contents();
        ob_end_clean();

        ob_start();
        include __DIR__ . '/../../resources/views/layout.php';
        $layout = ob_get_contents();
        ob_end_clean();
        
       return $layout;
    }
}