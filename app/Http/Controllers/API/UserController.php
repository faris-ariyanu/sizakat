<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request){
        try {
            $Query = User::with(["role","status"])->where('role',"!=","3");
            if($request->has('status')){
                $Query->where("status",$request->status);
            }

            if($request->has('key') && $request->key){
                $Query->where(function($sub) use($request) {
                    $sub->orWhereRaw("name like '%".strtolower($request->key)."%'")
                        ->orWhereRaw("email like '%".strtolower($request->key)."%'")
                        ->orWhereRaw("username like '%".strtolower($request->key)."%'")
                        ->orWhereRaw("code like '%".strtolower($request->key)."%'");
                });
            }

            if($request->has('orderby') && $request->has('sort')){
                $Query->orderby($request->orderby,$request->sort);
            }

            $total = $Query->count();

            if($request->has('limit') && $request->has('offset')){
                $Query->skip($request->offset)
                        ->take($request->limit);
            }

            if($request->has('id') && is_numeric($request->id)){
                $Query = $Query->where("id",$request->id);
                $total = $Query->count();
                $Query = $Query->first();
            }else{
                $Query = $Query->get();
            }

            $return = [
                "rows" => $Query,
                "total" => $total,
            ];

            return successResp("",$return);

        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function store(Request $request){
        try {

            $checkUsername  = User::where("username",$request->username)->count();
            if($checkUsername){
                return errorResp("Username sudah terpakai!",422);
            }

            $data                   = new User;
            $data->code             = now();
            $data->name             = $request->name;
            $data->username         = $request->username;
            $data->email            = $request->email;
            $data->role             = $request->role;
            $data->password         = app('hash')->make($request->password);
            $data->status           = $request->status;
            if($request->hasFile('avatar')){
                $data->avatar   = upload($request->avatar);
            }

            $data->save();
            return successResp("Berhasil menyimpan data");
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function update(Request $request){
        try {
            $data               = User::find($request->id);
            if( $data ){
                
                $checkUsername  = User::where("username",$request->username)
                                    ->where("username","!=",$data->username)
                                    ->count();
                if($checkUsername){
                    return errorResp("Username sudah terpakai!",422);
                }
                
                $data->name             = $request->name;
                $data->email            = $request->email;
                $data->username         = $request->username;
                $data->role             = $request->role;
                $data->status           = $request->status;
                if($request->has('password') && $request->password){
                    $data->password = app('hash')->make($request->password);
                }
                if($request->hasFile('avatar')){
                    $data->avatar   = upload($request->avatar);
                }

                $data->save();
                return successResp("Berhasil menyimpan data");
                
            }else{
                return errorResp("Data tidak ditemukan!",422);
            }
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function delete(Request $request){
        try {
            if($request->has('id') && $request->id){
                $data               = User::find($request->id);
                if( $data ){
                    destroy($data->avatar);
                    $data->delete();
                    return successResp("Berhasil menghapus data");
                }else{
                    return errorResp("Data tidak tersedia!",422);
                }
            }else{
                return errorResp("Data tidak tersedia!",422);
            }
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }
}
