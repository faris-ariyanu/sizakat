<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleMenu extends Model
{
    protected $table = "mst_role_menus";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role', 'menu' , 'permission'
    ];

    public $timestamps = false;

    public function getPermissionAttribute($value){
        return json_decode($value);
    }

}
