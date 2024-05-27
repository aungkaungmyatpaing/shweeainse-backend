<?php

namespace App\Models;

use App\Casts\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'image'
    ];

    protected $casts = [
        'image' => Image::class,
    ];

    public function product(){
        return $this->hasMany(Product::class,'sub_category_id','id');
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }
}
