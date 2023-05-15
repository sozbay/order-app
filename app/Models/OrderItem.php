<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    public $table = 'order_items';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'order_id ',
        'product_id',
        'quantity',
        'unitPrice',
        'created_at'
    ];

    public function product(){
        return $this->hasMany(Product::class,'id','product_id');
    }
}
