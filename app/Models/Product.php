<?php

// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'image_url', 'category_id'];

    // Define the relationship with the 'OrderItem' model
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Define the many-to-many relationship with 'User' model via 'wishlists' pivot table
    public function users()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items', 'product_id', 'order_id')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
