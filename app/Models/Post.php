<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;
use MongoDB\Laravel\Eloquent\Model;

class Post extends EloquentModel
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'blog_posts';

}
