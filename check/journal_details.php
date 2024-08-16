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


$authorsStmt = $pdo->query("SELECT * FROM authors");
$authors = $authorsStmt->fetchAll(PDO::FETCH_ASSOC);
$authorsById = [];
foreach ($authors as $author) {
    $authorsById[$author['id']] = $author;
}

$stmt = $pdo->prepare("SELECT author_id FROM journal_author WHERE journal_id = ?");
$stmt->execute([$journalId]);
$authorIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM journals WHERE id = ?");
    $stmt->execute([$journalId]);
    header("Location: view_journals.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Подробная информация о журнале</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom right, #76b2fe, #b69efe);
            margin: 0;
            padding: 0;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            width: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .container:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            font-size: 2.5em;
            font-weight: 600;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }

        p {
            font-size: 1.2em;
            margin-bottom: 15px;
            line-height: 1.6;
            color: #555;
        }

        .back-link, .edit-button, .delete-button {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            padding: 12px 20px;
            border-radius: 30px;
            text-align: center;
            transition: background-color 0.3s, transform 0.3s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .back-link {
            background-color: #6a89cc;
            border: 2px solid #6a89cc;
        }

        .back-link:hover {
            background-color: #4a69bd;
            transform: translateY(-2px);
        }

        .edit-button {
            background-color: #38ada9;
            border: 2px solid #38ada9;
            margin-left: 15px;
        }

        .edit-button:hover {
            background-color: #3c6382;
            transform: translateY(-2px);
        }

        .delete-button {
            background-color: #e74c3c;
            border: 2px solid #e74c3c;
            margin-left: 15px;
        }

        .delete-button:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        .author-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .author-list li {
            background: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .author-list li:hover {
            background: #f1f1f1;
        }
    </style>
    <script>
        function confirmDeletion(event) {
            if (!confirm('Вы уверены, что хотите удалить этот журнал? Это действие нельзя отменить.')) {
                event.preventDefault();
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($journal['title']) ?></h1>
        <p><strong>Дата выпуска:</strong> <?= htmlspecialchars($journal['release_date']) ?></p>
        <p><strong>Авторы:</strong> 
            <ul class="author-list">
                <?php
                foreach ($authorIds as $authorId) {
                    if (isset($authorsById[$authorId])) {
                        $author = $authorsById[$authorId];
                        echo '<li>' . htmlspecialchars($author['last_name'] . ' ' . $author['first_name'] . ' ' . $author['middle_name']) . '</li>';
                    }
                }
                ?>
            </ul>
        </p>
        <p><strong>Короткое описание:</strong> <?= htmlspecialchars($journal['short_description']) ?></p>
        <a href="view_journals.php" class="back-link">Вернуться к справочнику журналов</a>
        <a href="edit_journal.php?id=<?= $journalId ?>" class="edit-button">Редактировать</a>
        <form method="POST" style="display: inline;">
            <button type="submit" name="delete" class="delete-button" onclick="confirmDeletion(event)">Удалить</button>
        </form>
    </div>
</body>
</html>
