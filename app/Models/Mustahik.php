<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mustahik extends Model
{

    protected $table = 'mst_mustahik';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['*'];

    protected $appends = [
        "tgl_lahir"
    ];

    public function category(){
        return $this->belongsTo("App\Models\CategoryMustahik","category_id");
    }

    public function getPhotoAttribute($value)
    {
        if ($value){
            return asset('storage/uploads/'.$value);
        }else{
            return "https://www.gravatar.com/avatar/".md5($this->email).".jpg";
        }
    }

    public function getTglLahirAttribute(){
        return dateformat($this->birthdate,"d M Y");
    }
}
