<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Mail;
use App\Models\Zakat;
use App\Models\ZakatItem;
use App\Models\Periode;
use App\Models\Muzakki;
use App\Models\KualitasZakat;
use App\Exports\ZakatExport;
use Maatwebsite\Excel\Facades\Excel;

class ZakatController extends Controller
{
    public function index(Request $request){
        try {
            $user   = Auth::user(); 
            $Query  = Zakat::with('createdby');

            if($request->has('key') && $request->key){
                $Query->where(function($sub) use($request) {
                    $sub->orWhereRaw("transaction_number like '%".strtolower($request->key)."%'")
                        ->orWhereRaw("name like '%".strtolower($request->key)."%'");
                });
            }

            if($request->has('transaction_number') && $request->transaction_number){
                $Query->where("transaction_number","like", '%'.$request->transaction_number.'%');
            }

            if($request->has('status') && $request->status != ""){
                $Query->where("status",$request->status);
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

    public function report(Request $request){
        try {
            $user   = Auth::user(); 
            $Query  = ZakatItem::join('trx_zakat','trx_zakat.id','trx_zakat_items.transaction_id')
                            ->select("trx_zakat_items.*","trx_zakat.transaction_number")
                            ->where("trx_zakat.periode",$request->periode)
                            ->orderBy("created_at","desc");

            if($request->report_type == "date"){
                if($request->has('date_start') && $request->date_start){
                    $Query->whereDate('trx_zakat.created_at', '>=', dateformat($request->date_start,"Y-m-d"));
                }
        
                if($request->has('date_end') && $request->date_end){
                    $Query->whereDate('trx_zakat.created_at', '<=', dateformat($request->date_end,"Y-m-d"));
                }
            }else{
                $Query->whereDate('trx_zakat.created_at', date('Y-m-d'));
            }

            $filters = [];
            if($request->has('filter') && $request->filter){
                foreach(json_decode($request->filter) as $key => $val){
                    if($val == true){
                        $filters[] = $key;
                    }
                }
            }

            if(count($filters)){
                $Query->whereIn('trx_zakat_items.zakat_type_id',$filters);
            }

            $total = $Query->count();

            if($request->has('limit') && $request->has('offset')){
                $Query->skip($request->offset)
                        ->take($request->limit);
            }

            $return = [
                "rows" => $Query->get(),
                "total" => $total,
                "rekap" => self::getRekap($request),
            ];

            return successResp("",$return);

        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function transaksimuzakki(Request $request){
        try {
            $user   = Auth::user(); 
            $Query  = ZakatItem::join('trx_zakat','trx_zakat.id','trx_zakat_items.transaction_id')
                            ->select("trx_zakat_items.*","trx_zakat.transaction_number");

            if($request->has('date_start') && $request->date_start){
                $Query->whereDate('trx_zakat.created_at', '>=', dateformat($request->date_start,"Y-m-d"));
            }
                    
            if($request->has('date_end') && $request->date_end){
                $Query->whereDate('trx_zakat.created_at', '<=', dateformat($request->date_end,"Y-m-d"));
            }

            if($request->has('transaction_number') && $request->transaction_number){
                $Query->where("trx_zakat.transaction_number","like", '%'.$request->transaction_number.'%');
            }

            if($request->has('muzakki_name') && $request->muzakki_name){
                $Query->where("trx_zakat_items.muzakki_name","like", '%'.$request->muzakki_name.'%');
            }

            if($request->has('orderby') && $request->has('sort')){
                $Query->orderby($request->orderby,$request->sort);
            }
            
            $total = $Query->count();

            if($request->has('limit') && $request->has('offset')){
                $Query->skip($request->offset)
                        ->take($request->limit);
            }

            $return = [
                "rows" => $Query->get(),
                "total" => $total,
            ];

            return successResp("",$return);

        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function getRekap($request){
        // fitrah
        $rekap['ftr_beras']['total']    = ZakatItem::filterRekap($request)->where("zakat_type_id","FTR")->where("transaction_type","Beras")->count();
        $rekap['ftr_beras']['amount']   = ZakatItem::filterRekap($request)->where("zakat_type_id","FTR")->where("transaction_type","Beras")->sum('income_goods');
        $rekap['ftr_uang']['total']     = ZakatItem::filterRekap($request)->where("zakat_type_id","FTR")->where("transaction_type","Uang")->count();
        $rekap['ftr_uang']['amount']    = ZakatItem::filterRekap($request)->where("zakat_type_id","FTR")->where("transaction_type","Uang")->sum('income_value');
        
        // fidyah
        $rekap['fdy_beras']['total']    = ZakatItem::filterRekap($request)->where("zakat_type_id","FDY")->where("transaction_type","Beras")->count();
        $rekap['fdy_beras']['amount']   = ZakatItem::filterRekap($request)->where("zakat_type_id","FDY")->where("transaction_type","Beras")->sum('income_goods');
        $rekap['fdy_uang']['total']     = ZakatItem::filterRekap($request)->where("zakat_type_id","FDY")->where("transaction_type","Uang")->count();
        $rekap['fdy_uang']['amount']    = ZakatItem::filterRekap($request)->where("zakat_type_id","FDY")->where("transaction_type","Uang")->sum('income_value');
        
        // mal
        $rekap['mal']['total']      = ZakatItem::filterRekap($request)->where("zakat_type_id","MAL")->where("transaction_type","Uang")->count();
        $rekap['mal']['amount']     = ZakatItem::filterRekap($request)->where("zakat_type_id","MAL")->where("transaction_type","Uang")->sum('income_value');
        
        // sedekah
        $rekap['sdq_beras']['total']    = ZakatItem::filterRekap($request)->where("zakat_type_id","SDQ")->where("transaction_type","Beras")->count();
        $rekap['sdq_beras']['amount']   = ZakatItem::filterRekap($request)->where("zakat_type_id","SDQ")->where("transaction_type","Beras")->sum('income_goods');
        $rekap['sdq_uang']['total']     = ZakatItem::filterRekap($request)->where("zakat_type_id","SDQ")->where("transaction_type","Uang")->count();
        $rekap['sdq_uang']['amount']    = ZakatItem::filterRekap($request)->where("zakat_type_id","SDQ")->where("transaction_type","Uang")->sum('income_value');
        
        $rekap['total_beras']           = ZakatItem::filterRekap($request)->where("transaction_type","Beras")->sum('income_goods');
        $rekap['total_uang']            = ZakatItem::filterRekap($request)->whereIn("transaction_type",["Uang","Cek"])->sum('income_value');
        $rekap['total_muzakki']         = ZakatItem::filterRekap($request)->distinct("muzakki_id")->count();
        return $rekap;
    }

    public function reportDashboard(Request $request){
        try {
            $data               = self::getRekap($request);
            return successResp("Berhasil mengambil data",$data);
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function component(){
        try {
            $data['periode']        = Periode::where("status",1)->orderBy("year","desc")->first();
            $data['zakatquality']   = KualitasZakat::where("type_zakat",'FTR')->orderBy("value","asc")->get();
            $data['date']           = date('d M Y');
            $data['type']           = getTypeZakat();
            $data['paymentmethod']  = getPaymentMethod();
            $data['qrcode']         = url('images/qrcode-mbr.png');
            $data['bank']           = url('images/bank-mbr.jpeg');
            $data['muzakki']        = Muzakki::with('relations')->where("username",Auth::user()->username)->first();
            return successResp("",$data);

        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function store(Request $request){
        try {
            $user                       = Auth::user();
            $data                       = new Zakat;
            $data->transaction_number   = now();
            $data->muzakki_id           = $request->muzakki_id;
            $data->periode              = $request->periode;
            $data->payment_method_id    = $request->payment_method_id;
            $data->payment_method_name  = getPaymentMethodById($request->payment_method_id);
            $data->name                 = $request->name;
            $data->no_ktp               = $request->no_ktp;
            $data->relation             = $request->relation;
            $data->address              = $request->address;
            $data->phone                = $request->phone;
            $data->email                = $request->email;
            $data->status               = $user->role != 3 ? 2 : 0;
            $data->created_by           = $user->id;
            $data->unique_code          = getUniqueCodeTrx();
            $data->expire_time          = SumDateWithHour(now(),24);
            $data->save();
            self::insertItems($data,$request->items);

            return successResp("Berhasil menyimpan data");
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function update(Request $request){
        try {
            $data               = Zakat::find($request->id);
            if( $data ){
            
                $data->periode              = $request->periode;
                $data->muzakki_id           = $request->muzakki_id;
                $data->name                 = $request->name;
                $data->payment_method_id    = $request->payment_method_id;
                $data->payment_method_name  = getPaymentMethodById($request->payment_method_id);
                $data->no_ktp               = $request->no_ktp;
                $data->relation             = $request->relation;
                $data->address              = $request->address;
                $data->phone                = $request->phone;
                $data->email                = $request->email;
                $data->created_by           = Auth::id();
                $data->unique_code          = getUniqueCodeTrx();
                $data->expire_time          = SumDateWithHour(now(),24);
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
        $total_money    = 0;
        $total_goods    = 0;
        foreach($items as $row){
            $row    = (object)$row;
            foreach($row->zakat as $zak){
                $zak    = (object)$zak;
                $code   = $zak->zakat_type_id.(count($insert) + 1);
                $item['transaction_sub_id'] = $code;
                $item['transaction_id']     = $data->id;
                $item['zakat_type_id']      = $zak->zakat_type_id;
                $item['muzakki_id']         = $row->muzakki_id;
                $item['muzakki_name']       = $row->muzakki_name;
                $item['muzakki_phone']      = $row->muzakki_phone;
                $item['muzakki_email']      = $row->muzakki_email;
                $item['muzakki_address']    = $row->muzakki_address;
                $item['muzakki_ktp']        = $row->muzakki_ktp;
                $item['muzakki_relation']   = $row->muzakki_relation;
                $item['created_at']         = now();
                $item['updated_at']         = now();
                $item['jenis_fitrah']       = "";

                if($zak->zakat_type_id == "FDY"){
                    $item['transaction_type']   = $zak->transaction_type;
                    $item['zakat_quality_id']   = 0;
                    if($item['transaction_type'] == "Uang"){
                        $item['income_value'] = $zak->income;
                        $item['income_goods'] = 0;
                    }else{
                        $item['income_value'] = 0;
                        $item['income_goods'] = $zak->income;
                    }
                }

                if($zak->zakat_type_id == "SDQ" || $zak->zakat_type_id == "MAL"){
                    $item['transaction_type']   = $zak->transaction_type;
                    $item['zakat_quality_id']   = 0;
                    if($zak->transaction_type == "Beras"){
                        $item['income_value']       = 0;
                        $item['income_goods']       = $zak->income;
                    }else{
                        $item['income_value']       = $zak->income;
                        $item['income_goods']       = 0;
                    }
                }

                if($zak->zakat_type_id == "FTR"){
                    $item['transaction_type'] = $zak->jenis_fitrah;
                    $item['jenis_fitrah']     = $zak->jenis_fitrah;
                    if($zak->jenis_fitrah == "Uang"){
                        $item['zakat_quality_id']   = $zak->zakat_quality_id['id'];
                        $item['income_value']       = $zak->zakat_quality_id['value'];
                        $item['income_goods']       = 0;
                    }else if($zak->jenis_fitrah == "Beras"){
                        $item['zakat_quality_id']   = 0;
                        $item['income_value']       = 0;
                        $item['income_goods']       = 2.5;
                    }else{
                        if($zak->transaction_type == "Uang"){
                            $item['transaction_type']   = "Uang"; 
                            $item['zakat_quality_id']   = 0;
                            $item['income_value']       = $zak->income;
                            $item['income_goods']       = 0;
                        }else{
                            $item['transaction_type']   = "Beras"; 
                            $item['zakat_quality_id']   = 0;
                            $item['income_value']       = 0;
                            $item['income_goods']       = $zak->income;
                        }
                    }
                }

                $total_money += $item['income_value'];
                $total_goods += $item['income_goods'];
                $insert[] = $item;
            }
        }

        ZakatItem::where("transaction_id",$data->id)->delete();
        ZakatItem::insert($insert);

        $data->total_money = $total_money;
        $data->total_goods = $total_goods;
        $data->save();
    }

    public function delete(Request $request){
        try {
            if($request->has('id') && $request->id){
                $data               = Zakat::find($request->id);
                if( $data ){
                    ZakatItem::where("transaction_id",$request->id)->delete();
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

    public function updateStatus(Request $request){
        $user   = Auth::user();
        $zakat  = Zakat::where("id",$request->id)->first();
        if(!empty($zakat)){
            $zakat->status = $request->status;
            $zakat->save();

            return successResp("Berhasil mengubah status");
        }else{
            return errorResp("Transaksi tidak ditemukan!",422);
        }
    }

    
    public function doPrint(Request $request){
        if($request->q){
            $req        = json_decode(base64_decode($request->q));
            $Query      = Zakat::where("id",$req->id)->first();
            if($Query){
                $data['items']      = ZakatItem::where("transaction_id",$req->id)->with('zakat_quality')->get();
                $data['order']      = $Query;
                $data['user']       = $req->user;
                return view('prints.kwitansi',$data);
            }else{
                die("Order tidak ditemukan");
            }
        }
    }

    public function export(Request $request) 
    {
        if($request->has('getlink') && $request->getlink){
            $req = $request->all();
            unset($req['getlink']);
            $request    = http_build_query($req);
            return successResp("",[
                "url"   => url("zakat/export?".$request)
            ]);
        }else{
            $Query  = ZakatItem::join('trx_zakat','trx_zakat.id','trx_zakat_items.transaction_id')
                            ->select("trx_zakat_items.*","trx_zakat.transaction_number")
                            ->where("trx_zakat.periode",$request->periode)
                            ->orderBy("created_at","desc");

            if($request->report_type == "date"){
                if($request->has('date_start') && $request->date_start){
                    $Query->whereDate('trx_zakat.created_at', '>=', dateformat($request->date_start,"Y-m-d"));
                }
        
                if($request->has('date_end') && $request->date_end){
                    $Query->whereDate('trx_zakat.created_at', '<=', dateformat($request->date_end,"Y-m-d"));
                }
            }else{
                $Query->whereDate('trx_zakat.created_at', date('Y-m-d'));
            }

            $filters = [];
            if($request->has('filter') && $request->filter){
                foreach(json_decode($request->filter) as $key => $val){
                    if($val == true){
                        $filters[] = $key;
                    }
                }
            }

            if(count($filters)){
                $Query->whereIn('trx_zakat_items.zakat_type_id',$filters);
            }

            $rekap =self::getRekap($request);
            return Excel::download(new ZakatExport($Query->get(),$Query->count(),$rekap), 'Laporan Penerimaan Zakat '.date('d M Y').'.xlsx');
        }
    }

    public function payment(Request $request){
        try {
            $data               = Zakat::find($request->id);
            if( $data ){
            
                $data->sender_bank  = $request->sender_bank;
                $data->sender_name  = $request->sender_name;
                if($request->hasFile('receipt')){
                    $data->receipt  = upload($request->receipt);
                }
                $data->status       = 1;
                $data->save();
                return successResp("Berhasil menyimpan data");
                
            }else{
                return errorResp("Data tidak ditemukan!",422);
            }
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }
}
