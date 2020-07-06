<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

    public function checkPermission(Request $request){
        try {
            $user   = Auth::user();
            $menus  = Menu::join("mst_role_menus","mst_menus.id","=","mst_role_menus.menu")
                            ->where("mst_role_menus.role",$user->role)
                            ->where("mst_menus.link","/".$request->link)
                            ->select("mst_role_menus.permission");
            if( $menus->count() ){
                $permission = json_decode($menus->first()->permission);
                if($request->has('action') && $request->action){
                    if($permission->{$request->action}){
                        return successResp("",$permission);
                    }else{
                        return errorResp("Access Forbidden",403);
                    }
                } else {
                    return successResp("",$permission);
                }
            }else{
                return errorResp("Access Forbidden",403);
            }
        } catch (\Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function changePassword(Request $request){
        try {
            $request->validate([
                'old_password' => 'required',
                'password' => 'required|confirmed|min:6',
            ]);

            $user = new User;
            $condition = ['id' => Auth::user()->id];
            $dataUser = $user->getUser($condition);

            if (!empty($dataUser)) {
                if (password_verify($request->old_password, $dataUser->password)) {
                    $data = ['password' => bcrypt($request->password)];
                    $user = $user->updateUser($data, $dataUser->id);

                    return successResp("Successfully changed password.",$user);
                }else {
                    return errorResp("Old password does not match", 422);
                }
            }else{
                return errorResp("Access Forbidden",403);
            }
        } catch (\Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function editProfile(Request $request){
        try {
            $data               = Auth::user();
            if( $data ){
                if($request->email != $data->email){
                    $checkEmail     = User::where("email",$request->email)->count();
                    if($checkEmail){
                        return errorResp("email is not available!",422);
                    }
                }

                $data->name             = $request->name;
                $data->email            = $request->email;
                if($request->hasFile('avatar')){
                    $data->avatar   = upload($request->avatar);
                }

                $data->save();
                return successResp("Successfully saved data");
                
            }else{
                return errorResp("Data not available!",422);
            }
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }
}
