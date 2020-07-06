<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\KualitasZakat;

class KualitasZakatController extends Controller
{
    public function index(Request $request){
        try {
            $Query = KualitasZakat::query();

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

    public function type(Request $request){
        try {
        
            return successResp("",getTypeZakat());

        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function store(Request $request){
        try {

            $data                   = new KualitasZakat;
            $data->type_zakat       = $request->type_zakat;
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
            $data               = KualitasZakat::find($request->id);
            if( $data ){
                $data->type_zakat       = $request->type_zakat;
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
                $data               = KualitasZakat::find($request->id);
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
