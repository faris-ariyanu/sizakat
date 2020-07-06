<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QurbanItem extends Model
{

    protected $table = 'trx_qurban_item';

    const SEPERTIGA_DIAMBIL = true;
    const SEPERTIGA_TIDAK = false;

    const SUDAH_DIAMBIL = true;
    const BELUM_DIAMBIL = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['*'];
}
