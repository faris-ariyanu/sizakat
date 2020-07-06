<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Mustahik;
use App\Exports\MustahikExport;
use Maatwebsite\Excel\Facades\Excel;

class MustahikController extends Controller
{
    public function index(Request $request){
        try {
            $Query = Mustahik::with(["category"]);

            if($request->has('key') && $request->key){
                $Query->where(function($sub) use($request) {
                    $sub->orWhereRaw("name like '%".strtolower($request->key)."%'")
                        ->orWhereRaw("phone like '%".strtolower($request->key)."%'")
                        ->orWhereRaw("no_ktp like '%".strtolower($request->key)."%'")
                        ->orWhereRaw("mustahik_status like '%".strtolower($request->key)."%'")
                        ->orWhereRaw("rt like '%".strtolower($request->key)."%'")
                        ->orWhereRaw("rw like '%".strtolower($request->key)."%'");
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

            $checkKTP  = Mustahik::where("no_ktp",$request->no_ktp)->count();
            if($checkKTP){
                return errorResp("No KTP sudah terpakai!",422);
            }

            $data                   = new Mustahik;
            $data->name             = $request->name;
            $data->no_ktp           = $request->no_ktp;
            $data->address          = $request->address;
            $data->province         = $request->province;
            $data->regency          = $request->regency;
            $data->rt               = $request->rt;
            $data->rw               = $request->rw;
            $data->birthdate        = dateformat($request->birthdate,"Y-m-d");
            $data->age              = getAge($data->birthdate);
            $data->mustahik_status  = $request->mustahik_status;
            $data->family_size      = $request->family_size;
            $data->category_id      = $request->category_id;
            $data->phone            = $request->has('phone') ? $request->phone : "";
            $data->description      = $request->has('description') ? $request->description : "";
            if($request->hasFile('photo')){
                $data->photo    = upload($request->photo);
            }

            $data->save();
            return successResp("Berhasil menyimpan data");
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function update(Request $request){
        try {
            $data               = Mustahik::find($request->id);
            if( $data ){
                
                $checkKTP       = Mustahik::where("no_ktp",$request->no_ktp)
                                        ->where("no_ktp","!=",$data->no_ktp)
                                        ->count();
                if($checkKTP){
                    return errorResp("No KTP sudah terpakai!",422);
                }
                
                $data->name             = $request->name;
                $data->no_ktp           = $request->no_ktp;
                $data->address          = $request->address;
                $data->province         = $request->province;
                $data->regency          = $request->regency;
                $data->rt               = $request->rt;
                $data->rw               = $request->rw;
                $data->birthdate        = dateformat($request->birthdate,"Y-m-d");
                $data->age              = getAge($data->birthdate);
                $data->mustahik_status  = $request->mustahik_status;
                $data->family_size      = $request->family_size;
                $data->category_id      = $request->category_id;
                $data->phone            = $request->has('phone') ? $request->phone : "";
                $data->description      = $request->has('description') ? $request->description : "";
                if($request->hasFile('photo')){
                    destroy($data->photo);
                    $data->photo    = upload($request->photo);
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
                $data               = Mustahik::find($request->id);
                if( $data ){
                    destroy($data->photo);
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

    public function export(Request $request) 
    {
        if($request->has('getlink') && $request->getlink){
            return successResp("",[
                "url"   => url("mustahik/export")
            ]);
        }else{
            return Excel::download(new MustahikExport(Mustahik::with('category')->get(),Mustahik::count()), 'Laporan Mustahik '.date('d M Y').'.xlsx');
        }
    }
}
