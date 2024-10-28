<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPembelian extends Model
{
    use HasFactory;
    
    protected $table = 'history_pembelian';
    protected $guarded = ['id'];
}
