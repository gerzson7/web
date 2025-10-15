<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все данные библиотеки</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container data-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Все сохранённые заявки</h2>
            <a href="index.php" class="btn btn-primary">На главную</a>
        </div>

        <?php
        if(file_exists("data.txt")){
            $lines = file("data.txt", FILE_IGNORE_NEW_LINES);
            $totalRecords = count($lines);
            
            if($totalRecords > 0): ?>
                <div class="alert alert-info">
                    Всего заявок: <strong><?= $totalRecords ?></strong>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Имя</th>
                                <th>Номер билета</th>
                                <th>Жанр</th>
                                <th>Версия</th>
                                <th>Срок аренды</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($lines as $line): 
                                $data = explode(";", $line);
                                
                                if(count($data) == 5) {
                                    list($name, $id, $genre, $e_version, $duration) = $data;
                                } else {
                                    continue;
                                }
                                
                                $genreNames = [
                                    'detective' => 'Детектив',
                                    'fantasy' => 'Фантастика', 
                                    'love story' => 'Романтика',
                                    'drama' => 'Драма'
                                ];
                                $genreDisplay = $genreNames[$genre] ?? $genre;
                                
                                $durationNames = [
                                    'day' => 'День',
                                    'week' => 'Неделя',
                                    'month' => 'Месяц'
                                ];
                                $durationDisplay = $durationNames[$duration] ?? $duration;
                                
                                $versionBadge = ($e_version == 'yes') ? 
                                    '<span class="badge bg-success">Электронная</span>' : 
                                    '<span class="badge bg-secondary">Бумажная</span>';
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($name) ?></td>
                                    <td><?= htmlspecialchars($id) ?></td>
                                    <td><?= $genreDisplay ?></td>
                                    <td><?= $versionBadge ?></td>
                                    <td><?= $durationDisplay ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-warning text-center">
                    Нет данных для отображения
                </div>
            <?php endif;
        } else { ?>
            <div class="alert alert-warning text-center">
                Файл с данными не найден
            </div>
        <?php } ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>