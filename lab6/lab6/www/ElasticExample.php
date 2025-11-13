<?php

namespace App;

class ElasticExample
{
    private $client;

    public function __construct()
    {
        $this->client = ClientFactory::make('http://elasticsearch:9200/');
    }

    // Создание индекса
    public function createIndex($indexName)
    {
        try {
            $response = $this->client->put($indexName, [
                'json' => [
                    'settings' => [
                        'number_of_shards' => 1,
                        'number_of_replicas' => 0
                    ],
                    'mappings' => [
                        'properties' => [
                            'name' => ['type' => 'text'],
                            'ticket_number' => ['type' => 'keyword'],
                            'book_genre' => ['type' => 'keyword'],
                            'electronic_version' => ['type' => 'boolean'],
                            'rental_period' => ['type' => 'keyword'],
                            'created_at' => ['type' => 'date']
                        ]
                    ]
                ]
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // Добавление документа
    public function indexDocument($index, $data)
    {
        try {
            $data['created_at'] = date('c');
            $response = $this->client->post("$index/_doc", [
                'json' => $data
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // Поиск по всем полям
    public function search($index, $query)
    {
        try {
            $response = $this->client->get("$index/_search", [
                'json' => [
                    'query' => [
                        'multi_match' => [
                            'query' => $query,
                            'fields' => ['name', 'ticket_number', 'book_genre', 'rental_period']
                        ]
                    ]
                ]
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // Поиск по жанру
    public function searchByGenre($index, $genre)
    {
        try {
            $response = $this->client->get("$index/_search", [
                'json' => [
                    'query' => [
                        'match' => [
                            'book_genre' => $genre
                        ]
                    ]
                ]
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // Получить все документы
    public function getAll($index)
    {
        try {
            $response = $this->client->get("$index/_search", [
                'json' => [
                    'query' => [
                        'match_all' => []
                    ],
                    'sort' => [
                        ['created_at' => ['order' => 'desc']]
                    ]
                ]
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // Получить статистику
    public function getStats($index)
    {
        try {
            // Общее количество
            $countResponse = $this->client->get("$index/_count");
            $countData = json_decode($countResponse->getBody()->getContents(), true);
            
            // Количество электронных версий
            $electronicResponse = $this->client->get("$index/_search", [
                'json' => [
                    'query' => [
                        'term' => [
                            'electronic_version' => true
                        ]
                    ]
                ]
            ]);
            $electronicData = json_decode($electronicResponse->getBody()->getContents(), true);

            return [
                'total_count' => $countData['count'] ?? 0,
                'electronic_count' => $electronicData['hits']['total']['value'] ?? 0
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // Проверить существование индекса
    public function indexExists($indexName)
    {
        try {
            $response = $this->client->head($indexName);
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            return false;
        }
    }
}
