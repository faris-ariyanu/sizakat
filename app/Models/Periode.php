<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{

    protected $table = 'mst_periode';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['*'];

    public function status(){
        return $this->belongsTo("App\Models\Status","status","code")
                    ->where("type","global");
    }
}
