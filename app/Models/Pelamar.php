<?php

namespace App\Models;

class Pelamar extends User
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->role = 'pelamar';
    }
}
