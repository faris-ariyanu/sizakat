<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Muzakki extends Model
{

    protected $table = 'mst_muzakki';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['*'];

    protected $hidden = [
        'password',
    ];
    

    public function relations(){
        return $this->hasMany('App\Models\Muzakki','parent','id');
    }

    public function getPhotoAttribute($value)
    {
        if ($value){
            return asset('storage/uploads/'.$value);
        }else{
            return "https://www.gravatar.com/avatar/".md5($this->email).".jpg";
        }
    }
}
