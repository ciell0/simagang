<?php

namespace App\Models;

class Admin extends User
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->role = 'admin';
    }
}
