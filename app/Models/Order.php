<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public $table = 'orders';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'user_id',
        'total',
        'created_at'
    ];

    public function orderItems(){
        return $this->hasMany(OrderItem::class,'order_id','id');
    }
}
