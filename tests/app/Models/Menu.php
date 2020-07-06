<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Menu extends Model
{
    protected $table = "mst_menus";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'link', 'icon', 'parent', 'sequence', 'status',
    ];

    public function status()
    {
        return $this->belongsTo('App\Models\Status','status','code')
                    ->where("type","global");
    }

    public function children(){
        return $this->hasMany('App\Models\Menu','parent','id')
                ->join("mst_role_menus","mst_menus.id","=","mst_role_menus.menu")
                ->where("mst_role_menus.role",Auth::user()->role)
                ->select("mst_menus.id","mst_menus.parent","mst_menus.name","mst_menus.link as url","mst_menus.icon")
                ->orderBy("mst_menus.sequence","asc");
    }

}
