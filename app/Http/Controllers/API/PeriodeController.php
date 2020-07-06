<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Periode;

class PeriodeController extends Controller
{
    public function index(Request $request){
        try {
            $Query = Periode::with(["status"]);
            if($request->has('status')){
                $Query->where("status",$request->status);
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

            $checkTahun     = Periode::where("year",$request->year) ->count();
            if($checkTahun){
                return errorResp("Tahun sudah terpakai!",422);
            }
            
            $data                   = new Periode;
            $data->year             = $request->year;
            $data->description      = $request->has('description') ? $request->description : "";
            $data->status           = $request->status;
            $data->save();
            return successResp("Berhasil menyimpan data");
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function update(Request $request){
        try {
            $data               = Periode::find($request->id);
            if( $data ){
                
                $checkTahun     = Periode::where("year",$request->year)
                                        ->where("year","!=",$data->year)
                                        ->count();
                if($checkTahun){
                    return errorResp("Tahun sudah terpakai!",422);
                }
                
                $data->year             = $request->year;
                $data->description      = $request->has('description') ? $request->description : "";
                $data->status           = $request->status;
                $data->save();
                return successResp("Berhasil menyimpan data");
                
            }else{
                return errorResp("Data tidak ditemukan!",422);
            }
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function status(Request $request){
        try {
            if($request->has('id') && $request->id){
                $data               = Periode::find($request->id);
                if( $data ){
                    if($request->status == 1){
                        Periode::where("id","!=",$request->id)->update(["status"=>0]);
                    }

                    $data->status   = $request->status;
                    $data->save();
                    return successResp("Berhasil ".$request->status == 1 ? 'Mengaktifkan' : 'Nonaktifkan'." data");
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
