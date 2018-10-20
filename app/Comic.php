<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comic extends Model
{
    //

    protected $fillable = ['id', 'title', 'description', 'isbn', 'upc', 'format', ''];
}
