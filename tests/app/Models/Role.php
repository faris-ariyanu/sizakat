<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = "mst_roles";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'status'
    ];

    public function menus()
    {
        return $this->hasMany('App\Models\RoleMenu','role','id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Status','status','code')
                    ->where("type","global");
    }

}
