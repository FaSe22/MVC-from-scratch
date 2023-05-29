<?php

namespace app\src\core\orm\traits;

use app\src\core\Model;
use app\src\core\orm\PropertyMapper;

trait Instantiable
{
    /**
     * @param bool|array $entry
     * @param $fn
     * @param null $instance
     * @return Model
     */
    public static function getInstance(bool|array &$entry, $fn, $instance = null): Model
    {
        if (!$instance) {
            $instance = new (get_called_class());
        }

        $properties = get_class_vars(get_class($instance));
        PropertyMapper::$fn($properties, $entry, $instance);

        return $instance;

    }

    /**
     * @param bool|array $entries
     * @return void
     */
    public static function format(bool|array $entries): void
    {
      if(!empty($entries)){
          // die daten kommen so ["id" => 1 [0] => 1 "title" => "test" [1] => "test"],
          // entferne alle numerischen keys aus den ergebnissen
          // und wir erhalten ["id" => 1, "title" => "test"]
          array_map(function ($key) use ($entries) {
              if (is_numeric($key)) {
                  unset($entries[0][$key]);
              }
          }, array_keys($entries[0]));
      }
    }
}
