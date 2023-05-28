<?php

namespace app\src\core;

class View {

    /**
     * Ich hab hier gecheated, weil ich keine Ahnung hatte, wie das output-buffering geht.
     *
     * @param $name
     * @param $arguments
     * @return bool|string
     */
    public static function render($name, $arguments = []): bool|string
    {
        ob_start();
        if(is_array($arguments)){
            extract($arguments);
        }

        include_once('./src/views/'. $name . ".php");
        return ob_get_clean();
    }

}
