<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZakatItem extends Model
{
    protected $table = "trx_zakat_items";


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['*'];

    protected $appends = ['zakat_type'];
    
    public function zakat_quality_id(){
        return $this->belongsTo("App\Models\KualitasZakat","zakat_quality_id","id");
    }

    public function zakat_quality(){
        return $this->belongsTo("App\Models\KualitasZakat","zakat_quality_id","id");
    }

    public function getCreatedAtAttribute($val){
        return dateformat($val,"d M Y H:i");
    }

    public function getZakatTypeAttribute(){
        return getTypeZakatByCode($this->zakat_type_id); 
    }

    public function scopeFilterRekap($Query, $request)
    {
        $Query->join('trx_zakat','trx_zakat.id','trx_zakat_items.transaction_id')
                ->where("trx_zakat.periode",$request->periode)
                ->where("trx_zakat.status","2");

        if($request->report_type == "date"){
            if($request->has('date_start') && $request->date_start){
                $Query->whereDate('trx_zakat.created_at', '>=', dateformat($request->date_start,"Y-m-d"));
            }
    
            if($request->has('date_end') && $request->date_end){
                $Query->whereDate('trx_zakat.created_at', '<=', dateformat($request->date_end,"Y-m-d"));
            }
        }else if($request->report_type == "today"){
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
    } 

}
