<?php

namespace App\Models;

use Elastic\Elasticsearch\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'title',
        'brand',
        'category',
        'description',
        'price',
        'discount',
        'images'
    ];

    public static function booted()
    {
        static::created(function ($product) {
            app(Client::class)->index([
                'index' => 'products',
                'id' => $product->id,
                'body' => [
                    'title' => $product->title,
                    'description' => $product->description,
                    'price' => $product->price,
                ],
            ]);
        });

        static::updated(function ($product) {
            app(Client::class)->update([
                'index' => 'products',
                'id' => $product->id,
                'body' => [
                    'doc' => [
                        'title' => $product->title,
                        'description' => $product->description,
                        'price' => $product->price,
                    ]
                ],
            ]);
        });

        static::deleted(function ($product) {
            app(Client::class)->delete([
                'index' => 'products',
                'id' => $product->id,
            ]);
        });
    }
}

