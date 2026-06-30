<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get all of the products that are assigned this category.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
   
    /**
     * Get the sub categories for the category.
     */
    public function sub_categories()
    {
        return $this->hasMany(SubCategory::class);
    }
   public function subcategories()
    {
        return $this->hasMany(SubCategory::class, 'category_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    /**
     * Get all of the collections that are assigned this category.
     */
    public function collections()
    {
        return $this->belongsToMany(Collection::class);
    }
}
