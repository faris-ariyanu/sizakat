<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ZakatItem;
use Auth;

class Zakat extends Model
{
    protected $table = "trx_zakat";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['*'];

    protected $appends = ['date','items','print','qrcode','bank','show_uniquecode'];

    public static function boot() {
        parent::boot();
        
        static::created(function ($model) {
            $model->transaction_number    = "TRANSC".date('Ymd').$model->id;
            $model->save();
        });
    }

    public function getDateAttribute(){
        return dateformat($this->created_at,"d M Y H:i");
    }

    public function getExpireTimeAttribute($val){
        return dateformat($val,"d M Y H:i");
    }

    public function createdby(){
        return $this->belongsTo("App\Models\User","created_by");
    }

    public function getStatusAttribute($val){
        switch($val){
            case 0:
                return [
                    "id" => 0,
                    "name" => "Menunggu Pembayaran",
                    "class" => "danger",
                ];
            break;
            case 1:
                return [
                    "id" => 1,
                    "name" => "Menunggu Approval",
                    "class" => "warning",
                ];
            break;
            case 2:
                return [
                    "id" => 2,
                    "name" => "Pembayaran Diterima",
                    "class" => "success",
                ];
            break;
        }
    }

    public function getItemsAttribute(){
        $query = ZakatItem::where("transaction_id",$this->id)
                    ->select("id","muzakki_id","muzakki_name","muzakki_phone","muzakki_email",
                                "muzakki_address","muzakki_ktp","muzakki_relation")
                    ->distinct("muzakki_id")
                    ->get();
        foreach($query as $row){
            $row->zakat = ZakatItem::where("trx_zakat_items.muzakki_id",$row->muzakki_id)
                                ->where("transaction_id",$this->id)
                                ->with('zakat_quality_id')
                                ->get();
            foreach($row->zakat as $zak){
                if($zak->income_value != 0){
                    $zak->income = $zak->income_value;
                }else{
                    $zak->income = $zak->income_goods;
                }
            }
        }

        return $query;
    }

    public function getPrintAttribute(){
        $data = json_encode([
            "id" => $this->id,
            "transaction_number" => $this->transaction_number,
            "user" => Auth::user()->name,
        ]);
        $token = base64_encode($data);
        return url('print/'.$this->type.'?q='.$token);
    }

    public function getQrcodeAttribute($val){
        return url('images/qrcode-mbr.png');
    }

    public function getBankAttribute($val){
        return url('images/bank-mbr.jpeg');
    }

    public function getReceiptAttribute($val){
        return asset('storage/uploads/'.$val); 
    }

    public function getShowUniquecodeAttribute(){
        $query  = ZakatItem::where("transaction_id",$this->id)->get();
        $show   = false;
        foreach($query as $row){
            if($row->transaction_type != "Beras"){
                $show = true;
                break;
            }
        }
        return $show;
    }
}
