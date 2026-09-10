<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $guarded = [];

    public function detail()
    {
        return $this->hasMany(DetailPesanan::class);
    }
}