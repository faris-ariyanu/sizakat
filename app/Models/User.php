<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Role;

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
        'name', 'email', 'password', 'status', 'role','avatar','username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function boot() {
        parent::boot();
        
        static::created(function ($model) {
            $role           = Role::find($model->role);
            $model->code    = @$role->code .str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });
    }

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

    public function role(){
        return $this->belongsTo("App\Models\Role","role");
    }

    public function getAvatarAttribute($value)
    {
        if ($value){
            if(strpos($value, "https") !== false){
                return "https://www.gravatar.com/avatar/".md5($this->email).".jpg";
            }else{
                return asset('storage/uploads/'.$value);
            }
        }else{
            return "https://www.gravatar.com/avatar/".md5($this->email).".jpg";
        }
    }

    public function getUser($condition)
    {
        return User::where($condition)->first();
    }

    public function updateUser($data, $userId)
    {
        $user = User::find($userId);
        $user->fill($data);
        $user->save();

        return $user;
    }
}
