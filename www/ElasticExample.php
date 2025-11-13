<?php

namespace App;

use App\Helpers\ClientFactory;

class ElasticExample
{
    private $client;

    public function __construct()
    {
        $this->client = ClientFactory::make('http://elasticsearch:9200/');
    }

    public function createBooksIndex(string $index = 'books')
    {
        $mapping = [
            'settings' => [
                'number_of_shards' => 1,
                'number_of_replicas' => 0
            ],
            'mappings' => [
                'properties' => [
                    'title' => ['type' => 'text'],
                    'author' => ['type' => 'keyword'],
                    'genre' => ['type' => 'keyword'],
                    'year' => ['type' => 'integer'],
                    'rating' => ['type' => 'float'],
                    'description' => ['type' => 'text'],
                    'created_at' => ['type' => 'date']
                ]
            ]
        ];

        $response = $this->client->put("$index", ['json' => $mapping]);
        return $response->getBody()->getContents();
    }

    public function indexBook(string $index, string $id, array $data)
    {
        $response = $this->client->put("$index/_doc/$id", ['json' => $data]);
        return $response->getBody()->getContents();
    }

    public function searchBooks(string $index, string $query, int $size = 5)
    {
        $body = [
            'query' => [
                'multi_match' => [
                    'query' => $query,
                    'fields' => ['title^3', 'author', 'genre', 'description']
                ]
            ],
            'size' => $size
        ];

        $response = $this->client->get("$index/_search", [
            'body' => json_encode($body)
        ]);
        return $response->getBody()->getContents();
    }
}
