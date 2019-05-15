<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable=['name','cant','price','category_id'];

    public function category()
    {
    	return $this->belongsTo(Category::class);
    }

    public static function getCategories($products)
    {
    	foreach ($products as $key => $product) {
            $product->category;
        }
        return $products;
    }
}
