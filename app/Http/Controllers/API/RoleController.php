<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\RoleMenu;

class RoleController extends Controller
{
    public function index(Request $request){
        try {
            $Query = Role::with(["status","menus"]);

            if($request->has('key') && $request->key){
                    $Query->where(function($query) use($request) {
                            $query->orWhereRaw("name like '%".strtolower($request->key)."%'");
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

            $checkCode  = Role::where("code",$request->code)->count();
            if($checkCode){
                return errorResp("Kode sudah terpakai!",422);
            }

            $data               = new Role;
            $data->code         = $request->code;
            $data->name         = $request->name;
            $data->status       = $request->status;
            $data->save();
            self::StorePermission($data->id,$request->permission);
            return successResp("Berhasil menyimpan data");
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function update(Request $request){
        try {

            if($request->has('id') && is_numeric($request->id)){
                $data               = Role::find($request->id);
                if( $data ){
                    $checkCode  = Role::where("code",$request->code)
                                        ->where("code","!=",$data->code)
                                        ->count();
                    if($checkCode){
                        return errorResp("Kode sudah terpakai!",422);
                    }
                    
                    $data->code         = $request->code;
                    $data->name         = $request->name;
                    $data->status       = $request->status;
                    $data->save();
                    self::StorePermission($data->id,$request->permission);
                    return successResp("Berhasil menyimpan data");
                }else{
                    return errorResp("Data tidak ditemukan!",422);
                }
            }else{
                return errorResp("Data tidak ditemukan!",422);
            }
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function StorePermission($id,$permission){
        $insert     = [];
        foreach ($permission as $row) {
            
            $insertParent = false;
            if(count($row['childs'])){
                foreach ($row['childs'] as $child) {
                    if(self::setPermission($child['permission'])){
                        $insertParent = true;
                        $insert[]   = [
                            "role" => $id,
                            "menu" => $child['id'],
                            "permission" => json_encode($child['permission']),
                        ];
                    }
                }
            }

            if($insertParent || (!count($row['childs']) && self::setPermission($row['permission']))){
                $insert[]   = [
                    "role" => $id,
                    "menu" => $row['id'],
                    "permission" => json_encode($row['permission']),
                ];
            }
        }
        RoleMenu::where("role",$id)->delete();
        RoleMenu::insert($insert);
    }

    public function setPermission($permission){
        $isInsert = false;
        foreach ($permission as $row) {
            if($row){
                $isInsert = true;
            }
        }

        return $isInsert;
    }

    public function delete(Request $request){
        try {
            if($request->has('id') && is_numeric($request->id)){
                $data               = Role::find($request->id);
                if( $data ){
                    $data->delete();
                    $data->menus()->delete();
                    return successResp("Berhasil menghapus data");
                }else{
                    return errorResp("Data tidak ditemukan!",422);
                }
            }else{
                return errorResp("Data tidak ditemukan!",422);
            }
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }
}
