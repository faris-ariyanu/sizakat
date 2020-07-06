<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\TrxHewanQurban;

class TrxHewanQurbanController extends Controller
{
    public function index(Request $request){
        try {
            $Query = TrxHewanQurban::with('hewanQurban');

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

            if($request->hasFile('picture')){
                if($request->file('picture')->getSize() > 5000000){
                    return self::errorResp("Maksimal ukuran lampiran 5mb",200);
                }
            }

            $data                   = new TrxHewanQurban;
            $data->hewan_qurban_id  = $request->hewan_qurban_id;
            $data->periode          = $request->periode;
            $data->no_urut          = $request->no_urut;
            $data->status           = TrxHewanQurban::DITERIMA;
            $data->picture          = upload($request->file('picture'));

            $data->save();
            return successResp("Berhasil menyimpan data");
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function update(Request $request){
        try {
            $data               = TrxHewanQurban::find($request->id);
            if( $data ){

                if($request->hasFile('picture')){
                    if($request->file('picture')->getSize() > 5000000){
                        return self::errorResp("Maksimal ukuran lampiran 5mb",200);
                    }
                }

                $data->hewan_qurban_id  = $request->hewan_qurban_id;
                $data->periode          = $request->periode;
                $data->no_urut          = $request->no_urut;
                $data->status           = $request->status;
                $data->picture          = upload($request->file('picture'));
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
                $data               = TrxHewanQurban::find($request->id);
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
