<?php

use SQLite3;

class DB extends SQLite3 {
    public function __construct($filename)
    {
        $this->open($filename);
    }
}
