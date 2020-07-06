<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrxHewanQurban extends Model
{

    protected $table = 'trx_hewan_qurban';

    const DITERIMA = 1;
    const DIPOTONG = 2;
    const DICACAH = 3;
    const DIBAGIKAN = 4;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['*'];

    public function hewanQurban()
    {
        return $this->belongsTo("App\Models\HewanQurban","hewan_qurban_id","id");
    }
}
