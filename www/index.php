<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="card mt-4">
            <div class="card-body">
                <h1 class="card-title text-center mb-4">Главная страница библиотеки</h1>
                
                <?php if(isset($_SESSION['errors'])): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach($_SESSION['errors'] as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php unset($_SESSION['errors']); ?>
                <?php endif; ?>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Данные из сессии</h5>
                    </div>
                    <div class="card-body">
                        <?php if(isset($_SESSION['name'])): ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Имя:</strong> <?= htmlspecialchars($_SESSION['name']) ?></p>
                                    <p><strong>Номер билета:</strong> <?= htmlspecialchars($_SESSION['id']) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Жанр книги:</strong> <?= htmlspecialchars($_SESSION['genre']) ?></p>
                                    <p><strong>Электронная версия:</strong> <?= isset($_SESSION['e-version']) ? 'yes' : 'no' ?></p>
                                    <p><strong>Срок аренды:</strong> <?= htmlspecialchars($_SESSION['duration']) ?></p>
                                </div>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0">Данных пока нет.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="form.html" class="btn btn-primary me-md-2">Заполнить форму</a>
                    <a href="view.php" class="btn btn-outline-primary">Посмотреть все данные</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>