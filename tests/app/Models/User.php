<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'mst_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'status', 'role','avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // JWT Auth
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function status(){
        return $this->belongsTo("App\Models\Status","status","code")
                    ->where("type","global");
    }

    public function getAvatarAttribute($value)
    {
        if ($value){
            return asset('storage/uploads/'.$value);
        }else{
            return "https://www.gravatar.com/avatar/".md5($this->email).".jpg";
        }
    }
}
