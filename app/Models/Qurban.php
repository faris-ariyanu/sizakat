<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Qurban extends Model
{

    protected $table = 'trx_qurban';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['*'];

    protected $appends = ['print'];

    public function createdby(){
        return $this->belongsTo("App\Models\User","created_by");
    }

    public function hewanQurban(){
        return $this->belongsTo("App\Models\TrxHewanQurban","trx_hewan_qurban_id", "id");
    }

    public function items(){
        return $this->hasMany("App\Models\QurbanItem","trx_qurban_id", "id");
    }

    public function getPrintAttribute(){
        $data = json_encode([
            "id" => $this->id,
            "user" => Auth::user()->name,
        ]);
        $token = base64_encode($data);
        return url('print-qurban/?q='.$token);
    }
}
