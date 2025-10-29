<?php
class Student {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function add($name, $ticket_number, $book_genre, $electronic_version, $rental_period) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO students (name, ticket_number, book_genre, electronic_version, rental_period) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $ticket_number, $book_genre, $electronic_version, $rental_period]);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM students ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM students");
        return $stmt->fetch()['count'];
    }

    public function getElectronicCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM students WHERE electronic_version = 1");
        return $stmt->fetch()['count'];
    }
}
?>
