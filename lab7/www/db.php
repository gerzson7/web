<?php
class FileDB {
    private $dataFile = 'data/library_data.json';
    
    public function __construct() {
        // Просто проверяем существование файла
        if (!file_exists($this->dataFile)) {
            file_put_contents($this->dataFile, json_encode([]));
        }
    }
    
    public function insert($table, $data) {
        $allData = $this->getAllData();
        $id = uniqid();
        $data['id'] = $id;
        $data['created_at'] = date('Y-m-d H:i:s');
        
        if (!isset($allData[$table])) {
            $allData[$table] = [];
        }
        
        $allData[$table][$id] = $data;
        $this->saveAllData($allData);
        
        return $id;
    }
    
    public function select($table) {
        $allData = $this->getAllData();
        return $allData[$table] ?? [];
    }
    
    public function getStats() {
        $allData = $this->getAllData();
        $stats = [
            'total_requests' => 0,
            'by_genre' => [],
            'by_period' => []
        ];
        
        if (isset($allData['library_requests'])) {
            $stats['total_requests'] = count($allData['library_requests']);
            
            foreach ($allData['library_requests'] as $request) {
                $genre = $request['genre'];
                if (!isset($stats['by_genre'][$genre])) {
                    $stats['by_genre'][$genre] = 0;
                }
                $stats['by_genre'][$genre]++;
                
                $period = $request['rental_period'];
                if (!isset($stats['by_period'][$period])) {
                    $stats['by_period'][$period] = 0;
                }
                $stats['by_period'][$period]++;
            }
        }
        
        return $stats;
    }
    
    private function getAllData() {
        if (!file_exists($this->dataFile)) {
            return [];
        }
        $content = file_get_contents($this->dataFile);
        return json_decode($content, true) ?: [];
    }
    
    private function saveAllData($data) {
        file_put_contents($this->dataFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
