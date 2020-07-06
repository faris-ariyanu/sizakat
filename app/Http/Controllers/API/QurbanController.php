<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Mail;
use App\Models\Qurban;
use App\Models\QurbanItem;
use App\Models\Periode;

class QurbanController extends Controller
{
    public function index(Request $request){
        try {
            $user   = Auth::user(); 
            $Query  = Qurban::with('createdby')->with('hewanQurban.hewanQurban')->with('items');

            if($request->has('key') && $request->key){
                $Query->where(function($sub) use($request) {
                    $sub->orWhereRaw("name like '%".strtolower($request->key)."%'");
                });
            }

            if($request->has('date_start') && $request->date_start){
                $Query->whereDate('created_at', '>=', dateformat($request->date_start,"Y-m-d"));
            }
    
            if($request->has('date_end') && $request->date_end){
                $Query->whereDate('created_at', '<=', dateformat($request->date_end,"Y-m-d"));
            }

            if($user->role == 3){
                $Query->where("created_by",$user->id);
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
                if($Query){
                    $Query->role = $user->role;
                }
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
            $user                       = Auth::user();
            $data                       = new Qurban;
            $data->periode              = $request->periode;
            $data->trx_hewan_qurban_id    = $request->trx_hewan_qurban_id;
            $data->name                 = $request->name;
            $data->address              = $request->address;
            $data->phone                = $request->phone;
            $data->income_goods         = $request->income_goods;
            $data->income_value         = $request->income_value;
            $data->created_by           = $user->id;
            $data->status = true;
            $data->save();
            self::insertItems($data,$request->items);

            return successResp("Berhasil menyimpan data");
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function update(Request $request){
        try {
            $data               = Qurban::find($request->id);
            if( $data ){
            
                $user                       = Auth::user();
                $data->periode              = $request->periode;
                $data->trx_hewan_qurban_id    = $request->trx_hewan_qurban_id;
                $data->name                 = $request->name;
                $data->address              = $request->address;
                $data->phone                = $request->phone;
                $data->income_goods         = $request->income_goods;
                $data->income_value         = $request->income_value;
                $data->status         = $request->status;
                $data->created_by           = $user->id;
                $data->save();
                self::insertItems($data,$request->items);
                return successResp("Berhasil menyimpan data");
                
            }else{
                return errorResp("Data tidak ditemukan!",422);
            }
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function insertItems($data,$items){
        $insert         = [];
        foreach($items as $row){
            $row    = (object)$row;
            
            $item['trx_qurban_id']       = $data->id;
            $item['name']                = $row->name;
            $item['status_cacahan']      = $row->status_cacahan;
            $insert[] = $item;
        }

        QurbanItem::where("trx_qurban_id",$data->id)->delete();
        QurbanItem::insert($insert);
    }

    public function delete(Request $request){
        try {
            if($request->has('id') && $request->id){
                $data               = Qurban::find($request->id);
                if( $data ){
                    QurbanItem::where("trx_qurban_id",$request->id)->delete();
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
    
    public function doPrint(Request $request){
        if($request->q){
            $req        = json_decode(base64_decode($request->q));
            $Query      = Qurban::with('hewanQurban.hewanQurban')->where("id",$req->id)->first();
            if($Query){
                $data['items']      = QurbanItem::where("trx_qurban_id",$req->id)->get();
                $data['qurban']     = $Query;
                $data['user']       = $req->user;
                return view('prints.kwitansi-qurban',$data);
            }else{
                die("Order tidak ditemukan");
            }
        }
    }
}
