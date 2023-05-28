<?php

namespace app\src\core;

class PropertyMapper
{

    /**
     * @param array $properties
     * @param bool|array $entry
     * @param mixed $instance
     */
    public  static function mapColumnsToProperties(array $properties, bool|array $entry, mixed $instance):void
    {
        foreach (array_keys($properties) as $property) {
            $instance->$property = $entry[$property] ?? null;
        }
    }

    /**
     * @param array $properties
     * @param $args
     * @param mixed $instance
     * @return void
     */
    public static function mapPropertiesToColumns(array $properties, &$args, mixed $instance): void
    {
        foreach (array_keys($properties) as $property) {
            if ($property == "id") {
                continue;
            }
            $args[$property] = $instance->$property;
        }
    }

}
