<?php

namespace MVC\model;

use MVC\model\Model;

class User extends Model
{
    protected $table = "users";
    protected $fillable = ['name', 'phone_number', 'email', 'birth_date', 'country_id', 'gender'];
}
