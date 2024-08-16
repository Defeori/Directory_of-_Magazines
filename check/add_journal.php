<?php
require 'config.php';

$authorsStmt = $pdo->query("SELECT * FROM authors ORDER BY last_name ASC, first_name ASC, middle_name ASC");
$authors = $authorsStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $release_date_raw = $_POST['release_date'];

    $release_date = date('d.m.Y', strtotime($release_date_raw));

    $author_ids = $_POST['authors'];

    $stmt = $pdo->prepare("INSERT INTO journals (title, short_description, release_date) VALUES (?, ?, ?)");
    $stmt->execute([$title, $description, $release_date]);
    $journalId = $pdo->lastInsertId();

    foreach ($author_ids as $author_id) {
        $stmt = $pdo->prepare("INSERT INTO journal_author (journal_id, author_id) VALUES (?, ?)");
        $stmt->execute([$journalId, $author_id]);
    }

    header('Location: view_journals.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить журнал</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to bottom right, #76b2fe, #b69efe);
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #fff;
            font-size: 2.5em;
            font-weight: 600;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
            font-weight: bold;
        }

        form {
            max-width: 700px;
            margin: 30px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        textarea,
        input[type="date"],
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 16px;
            box-sizing: border-box;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        input[type="text"]:focus,
        textarea:focus,
        input[type="date"]:focus,
        select:focus {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            outline: none;
        }

        textarea {
            resize: vertical;
        }

        .button {
            padding: 14px 25px;
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            text-decoration: none;
            display: block;
            width: 100%;
            font-weight: bold;
        }

        .add-button {
            background-color: #6a89cc;
        }

        .add-button:hover {
            background-color: #546e9a;
            transform: translateY(-2px);
        }

        .back-button {
            background-color: #3498db;
        }

        .back-button:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }

        select {
            height: 150px;
            overflow-y: auto;
        }

        .buttons-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .buttons-container .button {
            flex: 1;
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <h1>Добавить журнал</h1>
    <form method="POST">
        <label>Название:</label>
        <input type="text" name="title" required>

        <label>Короткое описание:</label>
        <textarea name="description" rows="4"></textarea>

        <label>Дата выпуска:</label>
        <input type="date" name="release_date">

        <label>Авторы:</label>
        <select name="authors[]" multiple required>
            <?php foreach ($authors as $author): ?>
                <option value="<?= $author['id'] ?>">
                    <?= htmlspecialchars($author['last_name'] . ' ' . $author['first_name'] . ' ' . $author['middle_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="buttons-container">
            <a href="view_journals.php" class="button back-button">Вернуться к справочнику журналов</a>
            <button type="submit" class="button add-button">Добавить журнал</button>
        </div>
    </form>
</body>
</html>
