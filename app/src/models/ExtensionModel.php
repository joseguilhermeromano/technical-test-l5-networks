<?php

namespace Source\Models;
use Source\Database\Eloquent;

class ExtensionModel extends Eloquent{
    private $attributes = ["name", "extension", "agent", "online", "status"];

    protected static $table = 'extensions';

    public function __get($atributo) {
        if (array_key_exists($atributo, $this->attributes)) {
            return $this->attributes[$atributo];
        }
    }

    public function __set($atributo, $valor) {
        $this->attributes[$atributo] = $valor;
    }
}