<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserManagement\Role\RoleRequest;
use App\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request){
        try {
            $Query = Role::with(["status","menus"]);

            if($request->has('key') && $request->key){
                $Query = $Query->where(function($query) use($request) {
                            $query->orWhereRaw("name like '%".strtolower($request->key)."%'");
                        });
            }

            if($request->has('orderby') && $request->has('sort')){
                $Query    = $Query->orderby($request->orderby,$request->sort);
            }

            $total = $Query->count();

            if($request->has('limit') && $request->has('offset')){
                $Query      = $Query->skip($request->offset)
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

            return self::successResp("",$return);

        } catch (Exception $e) {
            return self::errorResp($e->getMessage(),422);
        }
    }

    public function store(RoleRequest $request){
        try {

            $data               = new Role;
            $data->name         = $request->name;
            $data->status       = $request->status;
            $data->save();
            return self::successResp("Berhasil menyimpan data");
        } catch (Exception $e) {
            return self::errorResp($e->getMessage(),422);
        }
    }

    public function update(RoleRequest $request){
        try {

            if($request->has('id') && is_numeric($request->id)){
                $data               = Role::find($request->id);
                if( $data ){
                    $data->name         = $request->name;
                    $data->status       = $request->status;
                    $data->save();
                    return self::successResp("Berhasil menyimpan data");
                }else{
                    return self::errorResp("Data tidak ditemukan");
                }
            }else{
                return self::errorResp("Data tidak ditemukan");
            }
        } catch (Exception $e) {
            return self::errorResp($e->getMessage(),422);
        }
    }

    public function delete(Request $request){
        try {
            if($request->has('id') && is_numeric($request->id)){
                $data               = Role::find($request->id);
                if( $data ){
                    $data->delete();
                    $data->menus()->delete();
                    return self::successResp("Berhasil menghapus data");
                }else{
                    return self::errorResp("Data tidak ditemukan");
                }
            }else{
                return self::errorResp("Data tidak ditemukan");
            }
        } catch (Exception $e) {
            return self::errorResp($e->getMessage(),422);
        }
    }
}
