<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Elastic\Elasticsearch\Client;
use App\Models\Product;

class ReindexProducts extends Command
{
    protected $signature = 'es:reindex-products';

    protected $description = 'Reindex all products into Elasticsearch';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(Client $client)
    {
        // 1. Удаляем индекс, если он есть
        if ($client->indices()->exists(['index' => 'products'])->asBool()) {
            $this->info('Deleting existing index [products]...');
            $client->indices()->delete(['index' => 'products']);
        }

        // 2. Создаём индекс заново с нужным маппингом
        $this->info('Creating index [products] with correct mapping...');
        $client->indices()->create([
            'index' => 'products',
            'body'  => [
                'mappings' => [
                    'properties' => [
                        // Поля, по которым вы делаете агрегации, пусть будут keyword
                        'brand' => [
                            'type' => 'keyword',
                        ],
                        'category' => [
                            'type' => 'keyword',
                        ],
                        'price' => [
                            'type' => 'float',
                        ],
                        'discount' => [
                            'type' => 'integer',
                        ],
                        'title' => [
                            'type' => 'text',
                        ],
                        'description' => [
                            'type' => 'text',
                        ],
                    ]
                ]
            ]
        ]);

        // 3. Берём товары из БД
        $products = Product::all();
        $this->info("Found {$products->count()} products...");

        // 4. Индексируем по одному (для наглядности), либо через bulk
        foreach ($products as $product) {
            $params = [
                'index' => 'products',
                'id'    => $product->id,
                'body'  => [
                    'title'       => $product->title,
                    'description' => $product->description,
                    'price'       => $product->price,
                    'brand'       => $product->brand,
                    'category'    => $product->category,
                    'discount'    => $product->discount,

                ],
            ];

            $client->index($params);
        }

        $this->info("Done indexing!");
        return 0;
    }
}
