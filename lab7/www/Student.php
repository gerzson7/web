<?php
if (!class_exists('Student')) {
    class Student {
        private $dataFile = 'data/library_data.json';
        
        public function __construct() {
            // Просто проверяем существование файла
            if (!file_exists($this->dataFile)) {
                file_put_contents($this->dataFile, json_encode([]));
            }
        }
        
        public function addLibraryRequest($name, $ticket_number, $genre, $electronic, $rental_period) {
            $allData = $this->getAllData();
            $id = uniqid();
            
            $data = [
                'id' => $id,
                'name' => $name,
                'ticket_number' => $ticket_number,
                'genre' => $genre,
                'electronic' => $electronic,
                'rental_period' => $rental_period,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            if (!isset($allData['library_requests'])) {
                $allData['library_requests'] = [];
            }
            
            $allData['library_requests'][$id] = $data;
            $this->saveAllData($allData);
            
            return $id;
        }
        
        public function getAllRequests() {
            $allData = $this->getAllData();
            return $allData['library_requests'] ?? [];
        }
        
        public function updateRequestStatus($id, $status) {
            $allData = $this->getAllData();
            if (isset($allData['library_requests'][$id])) {
                $allData['library_requests'][$id]['status'] = $status;
                $allData['library_requests'][$id]['processed_at'] = date('Y-m-d H:i:s');
                $this->saveAllData($allData);
            }
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
}
