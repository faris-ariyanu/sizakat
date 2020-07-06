<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Menu;
use Auth;

class ProfileController extends Controller
{
    public function index(){
        try {
            $user   = Auth::user();
            return successResp("",$user);
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function getMenu(){
        try {
            $user   = Auth::user();
            $menus  = Menu::join("mst_role_menus","mst_menus.id","mst_role_menus.menu")
                        ->where("mst_role_menus.role",$user->role)
                        ->where("mst_menus.status",1)
                        ->where("mst_menus.parent",0)
                        ->orderBy("sequence","asc")
                        ->select("mst_menus.id","mst_menus.name","mst_menus.link as url","mst_menus.icon","mst_menus.parent")
                        ->with('children')
                        ->get();

            return successResp("",$menus);
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }
}
