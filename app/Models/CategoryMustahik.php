<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryMustahik extends Model
{

    protected $table = 'mst_category_mustahik';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['*'];
}
