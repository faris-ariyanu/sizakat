<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Muzakki;
use App\Models\User;
use App\Exports\MuzakkiExport;
use Maatwebsite\Excel\Facades\Excel;

class MuzakkiController extends Controller
{
    public function index(Request $request){
        try {
            $Query = Muzakki::with('relations');

            if($request->has('parent') && is_numeric($request->parent)){
                $Query->where("parent",$request->parent);
            }

            if($request->has('key') && $request->key){
                $Query->where(function($sub) use($request) {
                    $sub->orWhereRaw("name like '%".strtolower($request->key)."%'")
                        ->orWhereRaw("email like '%".strtolower($request->key)."%'")
                        ->orWhereRaw("no_ktp like '%".strtolower($request->key)."%'")
                        ->orWhereRaw("username like '%".strtolower($request->key)."%'")
                        ->orWhereRaw("phone like '%".strtolower($request->key)."%'");
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

            $checkKTP  = Muzakki::where("no_ktp",$request->no_ktp)->count();
            if($checkKTP){
                return errorResp("No KTP sudah terpakai!",422);
            }

            $checkUsername  = User::where("username",$request->username)->count();
            if($checkUsername){
                return errorResp("Username sudah terpakai!",422);
            }

            $data                   = new Muzakki;
            $data->name             = $request->name;
            $data->no_ktp           = $request->no_ktp;
            $data->username         = $request->username;
            $data->password         = app('hash')->make($request->password);
            $data->email            = $request->has('email') ? $request->email : "";
            $data->address          = $request->has('address') ? $request->address : "";
            $data->phone            = $request->has('phone') ? $request->phone : "";
            $data->muzakki_status   = $request->muzakki_status;
            $data->parent           = $request->has('parent') ? $request->parent : 0;
            $data->relation         = $request->has('relation') ? $request->relation : "";
            if($request->hasFile('photo')){
                $data->photo   = upload($request->photo);
            }
            $data->registered_date  = now();
            $data->save();

            $user                   = new User;
            $user->code             = now();
            $user->name             = $data->name;
            $user->username         = $data->username;
            $user->email            = $data->email ? $data->email : "-";
            $user->role             = 3;
            $user->password         = $data->password;
            $user->status           = $data->muzakki_status == "activated" ? 1 : 0;
            $user->avatar           = $data->photo;
            $user->save();

            return successResp("Berhasil menyimpan data",$data);
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function update(Request $request){
        try {
            $data               = Muzakki::find($request->id);
            if( $data ){

                $username       = $data->username;
                
                $checkKTP       = Muzakki::where("no_ktp",$request->no_ktp)
                                        ->where("no_ktp","!=",$data->no_ktp)
                                        ->count();
                if($checkKTP){
                    return errorResp("No KTP sudah terpakai!",422);
                }

                $checkUsername  = User::where("username",$request->username)
                                    ->where("username","!=",$data->username)
                                    ->count();
                if($checkUsername){
                    return errorResp("Username sudah terpakai!",422);
                }
                
                $data->name             = $request->name;
                $data->no_ktp           = $request->no_ktp;
                $data->username         = $request->username;
                $data->email            = $request->has('email') ? $request->email : "";
                $data->address          = $request->has('address') ? $request->address : "";
                $data->phone            = $request->has('phone') ? $request->phone : "";
                $data->muzakki_status   = $request->muzakki_status;
                $data->parent           = $request->has('parent') ? $request->parent : 0;
                $data->relation         = $request->has('relation') ? $request->relation : "";

                if($request->has('password') && $request->password){
                    $data->password = app('hash')->make($request->password);
                }

                if($request->hasFile('photo')){
                    destroy($data->photo);
                    $data->photo   = upload($request->photo);
                }
                $data->registered_date  = now();
                $data->save();

                $user                   = User::firstOrNew(["username" => $username]);
                $user->name             = $data->name;
                $user->username         = $data->username;
                $user->email            = $data->email ? $data->email : "-";
                $user->role             = 3;
                $user->password         = $data->password;
                $user->status           = $data->muzakki_status == "activated" ? 1 : 0;
                $user->avatar           = $data->photo;
                $user->save();

                return successResp("Berhasil menyimpan data");
                
            }else{
                return errorResp("Data tidak ditemukan!",422);
            }
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function relation(Request $request){
        try {
        
            return successResp("",getStrukturKeluarga());

        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function delete(Request $request){
        try {
            if($request->has('id') && $request->id){
                $data               = Muzakki::find($request->id);
                if( $data ){
                    User::where("username",$data->username)->delete();
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
                "url"   => url("muzakki/export")
            ]);
        }else{
            return Excel::download(new MuzakkiExport(Muzakki::all(),Muzakki::count()), 'Laporan Muzakki '.date('d M Y').'.xlsx');
        }
    }
}
