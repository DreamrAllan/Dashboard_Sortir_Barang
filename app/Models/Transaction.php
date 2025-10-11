<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'item_id',
        'type',
        'quantity',
        'total_price',
        'notes',
        'transaction_date'
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public static function generateCode($type)
    {
        $prefix = $type === 'masuk' ? 'TM' : 'TK';
        $lastTransaction = self::where('type', $type)->latest('id')->first();
        $number = $lastTransaction ? intval(substr($lastTransaction->transaction_code, 2)) + 1 : 1;
        return $prefix . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}