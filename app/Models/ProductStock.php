<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'color_id', 'size_id','quantity'];


    public function product_sizes()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }
    public function product_color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
