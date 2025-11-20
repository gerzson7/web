<?php
require 'QueueManager.php';
require 'Student.php';

echo "🚀 Запуск обработчика заявок библиотеки...\n";
echo "=========================================\n";

$queueManager = new QueueManager();
$student = new Student();

$queueManager->consume(function($data) use ($student) {
    echo "📚 Обрабатываю заявку #{$data['request_id']} от: {$data['name']}\n";
    echo "🎫 Номер билета: {$data['ticket_number']}\n";
    echo "📖 Жанр: {$data['genre']}\n";
    echo "💾 Формат: {$data['electronic']}\n";
    echo "⏱️ Срок: {$data['rental_period']}\n";
    
    sleep(2);
    
    $student->updateRequestStatus($data['request_id'], 'completed');
    
    $logEntry = [
        'request_id' => $data['request_id'],
        'processed_at' => date('Y-m-d H:i:s'),
        'reader_name' => $data['name'],
        'status' => 'completed'
    ];
    
    file_put_contents('data/processing.log', json_encode($logEntry, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
    
    echo "✅ Книга подготовлена к выдаче! (ID: {$data['request_id']})\n";
    echo "---\n";
});
