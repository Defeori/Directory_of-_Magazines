<?php
require 'config.php';

$journalId = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM journals WHERE id = ?");
$stmt->execute([$journalId]);
$journal = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$journal) {
    echo "Журнал не найден!";
    exit;
}

$authorsStmt = $pdo->query("SELECT * FROM authors ORDER BY last_name, first_name, middle_name");
$authors = $authorsStmt->fetchAll(PDO::FETCH_ASSOC);

$authorsStmt = $pdo->prepare("SELECT author_id FROM journal_author WHERE journal_id = ?");
$authorsStmt->execute([$journalId]);
$selectedAuthorIds = $authorsStmt->fetchAll(PDO::FETCH_COLUMN);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("DELETE FROM journal_author WHERE journal_id = ?");
        $stmt->execute([$journalId]);
        $stmt = $pdo->prepare("DELETE FROM journals WHERE id = ?");
        $stmt->execute([$journalId]);
        $pdo->commit();
        header("Location: view_journals.php");
        exit;
    } else {

        $title = $_POST['title'];
        $description = $_POST['description'];
        $release_date_raw = $_POST['release_date'];
        
        $release_date = date('d.m.Y', strtotime($release_date_raw));
        $newAuthorIds = $_POST['authors'];

        $pdo->beginTransaction();

        $stmt = $pdo->prepare("UPDATE journals SET title = ?, short_description = ?, release_date = ? WHERE id = ?");
        $stmt->execute([$title, $description, $release_date, $journalId]);

        $stmt = $pdo->prepare("DELETE FROM journal_author WHERE journal_id = ?");
        $stmt->execute([$journalId]);

        foreach ($newAuthorIds as $authorId) {
            $stmt = $pdo->prepare("INSERT INTO journal_author (journal_id, author_id) VALUES (?, ?)");
            $stmt->execute([$journalId, $authorId]);
        }

        $pdo->commit();

        header("Location: view_journals.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать журнал</title>
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
            margin-top: 30px;
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
            resize: none;
        }

        .button-container {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

        button {
            padding: 12px 20px;
            background-color: #6a89cc;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            height: 50px; 
            line-height: 26px; 
        }

        button:hover {
            background-color: #4a69bd;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }

        button[name="delete"] {
            background-color: #e74c3c;
        }

        button[name="delete"]:hover {
            background-color: #c0392b;
        }

        select {
            height: 150px; 
            overflow-y: auto;
        }

        .return-button {
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 12px 20px;
            height: 25px; 
            line-height: 26px; 
        }

        .return-button:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <h1>Редактировать журнал</h1>
    <form method="POST">
        <label>Название:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($journal['title']) ?>" required>

        <label>Короткое описание:</label>
        <textarea name="description" rows="4"><?= htmlspecialchars($journal['short_description']) ?></textarea>

        <label>Дата выпуска:</label>
        <input type="date" name="release_date" value="<?= htmlspecialchars(date('Y-m-d', strtotime($journal['release_date']))) ?>">

        <label>Авторы:</label>
        <select name="authors[]" multiple required>
            <?php foreach ($authors as $author): ?>
                <option value="<?= $author['id'] ?>" <?= in_array($author['id'], $selectedAuthorIds) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($author['last_name'] . ' ' . $author['first_name'] . ' ' . $author['middle_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="button-container">
            <button type="submit">Сохранить изменения</button>
            <button type="submit" name="delete" value="1">Удалить журнал</button>
            <a href="view_journals.php" class="return-button">Вернуться к справочнику журналов</a>
        </div>
    </form>
</body>
</html>
