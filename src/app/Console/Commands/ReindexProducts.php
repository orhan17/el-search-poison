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
        if ($client->indices()->exists(['index' => 'products'])->asBool()) {
            $this->info('Deleting existing index [products]...');
            $client->indices()->delete(['index' => 'products']);
        }

        $this->info('Creating index [products] with correct mapping...');
        $client->indices()->create([
            'index' => 'products',
            'body'  => [
                'mappings' => [
                    'properties' => [
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

        $products = Product::all();
        $this->info("Found {$products->count()} products...");

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
