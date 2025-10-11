<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category_id',
        'stock',
        'price',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public static function generateCode()
    {
        $lastItem = self::latest('id')->first();
        $number = $lastItem ? intval(substr($lastItem->code, 3)) + 1 : 1;
        return 'ITM' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}