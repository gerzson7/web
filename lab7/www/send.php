<?php
require 'QueueManager.php';
require 'Student.php';

if ($_POST) {
    try {
        $name = htmlspecialchars($_POST['name']);
        $ticket_number = htmlspecialchars($_POST['ticket_number']);
        $genre = $_POST['genre'];
        $electronic = isset($_POST['electronic']) ? 'Да' : 'Нет';
        $rental_period = $_POST['rental_period'];
        
        $student = new Student();
        $requestId = $student->addLibraryRequest($name, $ticket_number, $genre, $electronic, $rental_period);
        
        $requestData = [
            'request_id' => $requestId,
            'name' => $name,
            'ticket_number' => $ticket_number,
            'genre' => $genre,
            'electronic' => $electronic,
            'rental_period' => $rental_period,
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => 'library_request'
        ];

        $queueManager = new QueueManager();
        $queueManager->publish($requestData);
        
        echo "✅ Заявка #{$requestId} отправлена в обработку!";
        
    } catch (Exception $e) {
        echo "❌ Ошибка: " . $e->getMessage();
    }
} else {
    echo "❌ Нет данных для отправки";
}
