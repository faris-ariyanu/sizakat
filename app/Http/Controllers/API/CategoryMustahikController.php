<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\CategoryMustahik;

class CategoryMustahikController extends Controller
{
    public function index(Request $request){
        try {
            $Query = CategoryMustahik::query();

            if($request->has('key') && $request->key){
                $Query->where(function($sub) use($request) {
                    $sub->orWhereRaw("name like '%".strtolower($request->key)."%'")
                        ->orWhereRaw("description like '%".strtolower($request->key)."%'")
                        ->orWhereRaw("value like '%".strtolower($request->key)."%'");
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

            $data                   = new CategoryMustahik;
            $data->name             = $request->name;
            $data->description      = $request->description ;
            $data->value            = $request->value;
            $data->save();
            return successResp("Berhasil menyimpan data");
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function update(Request $request){
        try {
            $data               = CategoryMustahik::find($request->id);
            if( $data ){
                $data->name             = $request->name;
                $data->description      = $request->description ;
                $data->value            = $request->value;
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
                $data               = CategoryMustahik::find($request->id);
                if( $data ){
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
