<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class Product extends EloquentModel
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'productos';
    public $timestamps = false;
}
