<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPenjualan extends Model
{
    use HasFactory;
    
    protected $table = 'history_penjualan';
    protected $guarded = ['id'];
}
