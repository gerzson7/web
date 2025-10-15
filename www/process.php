<?php
session_start();

// Получаем данные формы через $_POST
$name = htmlspecialchars($_POST['name']);
$id = htmlspecialchars($_POST['id']);
$genre = htmlspecialchars($_POST['genre']);
$e_version = htmlspecialchars($_POST['e-version'] ?? 'нет');
$duration = htmlspecialchars($_POST['duration']);

// Шаг 6. Проверка корректности данных
$errors = [];

// Имя не должно быть пустым
if(empty($name)) {
    $errors[] = "Имя не может быть пустым";
}

// Номер билета должен быть числом и не пустым
if(empty($id)) {
    $errors[] = "Номер билета не может быть пустым";
} elseif(!is_numeric($id)) {
    $errors[] = "Номер билета должен быть числом";
}

// Жанр не должен быть пустым
if(empty($genre)) {
    $errors[] = "Жанр книги не может быть пустым";
}

// Срок аренды не должен быть пустым
if(empty($duration)) {
    $errors[] = "Срок аренды не может быть пустым";
}

// Если есть ошибки, не сохраняем данные и выводим сообщения
if(!empty($errors)){
    $_SESSION['errors'] = $errors;
    header("Location: index.php");
    exit();
}

// Если ошибок нет, сохраняем данные
// Сохраняем в сессию
$_SESSION['name'] = $name;
$_SESSION['id'] = $id;
$_SESSION['genre'] = $genre;
$_SESSION['e-version'] = $e_version;
$_SESSION['duration'] = $duration;

// Сохранение данных в файл data.txt
$line = $name . ";" . $id . ";" . $genre . ";" . $e_version . ";" . $duration . "\n";
file_put_contents("data.txt", $line, FILE_APPEND);

// После сохранения в сессию перенаправляем обратно на главную страницу
header("Location: index.php");
exit();
?>