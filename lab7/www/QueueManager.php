<?php
class QueueManager {
    private $queueFile = 'data/message_queue.json';

    public function publish($data) {
        $queue = [];
        if (file_exists($this->queueFile)) {
            $queue = json_decode(file_get_contents($this->queueFile), true) ?: [];
        }
        
        $queue[] = [
            'id' => uniqid(),
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        file_put_contents($this->queueFile, json_encode($queue, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return true;
    }

    public function consume($callback) {
        echo "📚 Ожидаем заявки в библиотеку...\n";
        echo "💡 Для остановки нажмите Ctrl+C\n";
        
        $running = true;
        
        while ($running) {
            if (file_exists($this->queueFile)) {
                $queue = json_decode(file_get_contents($this->queueFile), true) ?: [];
                
                if (!empty($queue)) {
                    $message = array_shift($queue);
                    file_put_contents($this->queueFile, json_encode($queue, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    
                    echo "📥 Получена заявка: " . json_encode($message['data']) . "\n";
                    $callback($message['data']);
                    echo "✅ Заявка обработана\n";
                }
            }
            
            sleep(2);
        }
        
        echo "🛑 Обработчик остановлен\n";
    }
}