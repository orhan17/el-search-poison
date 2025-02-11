<?php

namespace App\Http\Controllers;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function search(Request $request, Client $client): JsonResponse
    {
        $query = $request->input('q', '');

        $params = [
            'index' => 'products',
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query'  => $query,
                        'fields' => ['title', 'description']
                    ]
                ]
            ],
            'size' => 20

        ];

        $response = $client->search($params);
        $hits = $response['hits']['hits'];

        $results = collect($hits)->map(function ($hit) {
            return [
                'id'    => $hit['_id'],
                'score' => $hit['_score'],
                'title' => $hit['_source']['title'] ?? null,
                'desc'  => $hit['_source']['description'] ?? null,
            ];
        });

        return response()->json($results);
    }
}
