<?php
require 'db.php';
require 'Student.php';

$student = new Student($pdo);

$name = htmlspecialchars($_POST['name']);
$ticket_number = htmlspecialchars($_POST['ticket_number']);
$book_genre = htmlspecialchars($_POST['book_genre'] ?? '');
$electronic_version = isset($_POST['electronic_version']) ? 1 : 0;
$rental_period = $_POST['rental_period'] ?? '';

$student->add($name, $ticket_number, $book_genre, $electronic_version, $rental_period);

header("Location: index.php");
exit();
?>
