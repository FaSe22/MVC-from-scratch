<?php

namespace app\src\models;

use app\src\repositories\BaseRepository;

abstract class Model
{

    public int $id;

    public function save(): static
    {
        $properties = get_class_vars(get_class($this));
        //Erstellung der Args for die create Methode
        $args = [];
        foreach (array_keys($properties) as $property) {
            if ($property == "id") continue;
            $args[$property] = $this->$property;
        }
        //Wir nehmen die neue Id und schreiben sie in die Instanz
        $id = BaseRepository::create($this, $args);
        $this->id = $id;
        return $this;
    }

    public function delete(): void
    {
        BaseRepository::delete($this);
    }

    public static function create($args)
    {
        // Erstellung einer neuen Instanz
        $instance = (new (get_called_class()));
        // Zuweisung der Properties
        $properties = get_class_vars(get_class($instance));
        foreach (array_keys($properties) as $property) {
            if ($property == "id") continue;
            $instance->$property = $args[$property];
        }
        //Wir nehmen die neue Id und schreiben sie in die Instanz
        $id = (BaseRepository::create($instance, $args));
        $instance->id = $id;
        return $instance;
    }

    public static function get()
    {
        $entries = BaseRepository::get(static::getTableName());
        // die daten kommen so ["id" => 1 [0] => 1 "title" => "test" [1] => "test"], entferne alle numerischen keys aus den ergebnissen
        // und wir erhalten ["id" => 1, "title" => "test"]
        array_map(function ($array_key) use ($entries) {
            if (is_numeric($array_key)) unset($entries[0][$array_key]);
        }, array_keys($entries[0]));

        //wir erstellen für jeden eintrag eine eigene instanz
        $instances = [];

        foreach ($entries as $model) {
            // Erstellung einer neuen Instanz
            $instance = (new (get_called_class()));
            // zuweisung der column werde zu den properties
            // holen wir alle properties des models
            $props = get_class_vars(get_class($instance));
            foreach (array_keys($props) as $key) {
                $instance->$key = $model[$key];
            }

            $instances[] = $instance;
        }

        return $instances;


    }

    public static function find(int $id)
    {
        $entry = BaseRepository::find(static::getTableName(), $id);

        $instance = (new (get_called_class()));
        $props = get_class_vars(get_class($instance));
        foreach (array_keys($props) as $key) {
            $instance->$key = $entry[0][$key];
        }

        return $instance;

    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        $string = str_replace('\\', '/', get_called_class());
        return (strtolower(basename($string)) . "s");
    }


}