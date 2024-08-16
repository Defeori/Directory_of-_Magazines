<?php
require 'config.php';


$searchQuery = "";
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $stmt = $pdo->prepare("SELECT * FROM journals WHERE title LIKE ?");
    $stmt->execute(['%' . $searchQuery . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM journals");
}
$journals = $stmt->fetchAll(PDO::FETCH_ASSOC);


$authorsStmt = $pdo->query("SELECT * FROM authors");
$authors = $authorsStmt->fetchAll(PDO::FETCH_ASSOC);
$authorsById = [];
foreach ($authors as $author) {
    $authorsById[$author['id']] = $author;
}


$authorsByJournal = [];
$journalIds = array_column($journals, 'id');
if (!empty($journalIds)) {
    $inClause = implode(',', array_fill(0, count($journalIds), '?'));
    $stmt = $pdo->prepare("SELECT journal_id, author_id FROM journal_author WHERE journal_id IN ($inClause)");
    $stmt->execute($journalIds);
    $journalAuthors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($journalAuthors as $journalAuthor) {
        $journalId = $journalAuthor['journal_id'];
        $authorId = $journalAuthor['author_id'];
        $authorsByJournal[$journalId][] = $authorId;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Справочник журналов</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
            color: #333;
            background: linear-gradient(to bottom right, #76b2fe, #b69efe);
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #fff;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
            font-size: 2.5em;
            letter-spacing: 1px;
        }

        .controls {
            text-align: center;
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .controls > * {
            display: flex;
            align-items: center;
        }

        input[type="text"] {
            padding: 12px 18px;
            width: 300px;
            border-radius: 25px;
            border: none;
            font-size: 16px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        input[type="text"]:focus {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            outline: none;
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
            font-weight: bold;

        }

        button:hover {
            background-color: #4a69bd;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }

        .add-button {
            background-color: #38ada9;
        }

        .add-button:hover {
            background-color: #3c6382;
        }

        .authors-button {
            background-color: #f39c12;
        }

        .authors-button:hover {
            background-color: #e67e22;
        }

        .logout-button {
            background-color: #e74c3c;
            display: flex;
            align-items: center;
            gap: 5px;
            order: -1;
        }

        .logout-button:hover {
            background-color: #c0392b;
        }

        .logout-button:before {
            content: "\2192"; 
            margin-right: 5px;
        }

        ul {
            list-style-type: none;
            padding: 0;
            max-width: 1200px;
            margin: 20px auto;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        li {
            background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            width: calc(25% - 20px);
            min-width: 250px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        li:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        a {
            text-decoration: none;
            color: #2c3e50;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 10px;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #6a89cc;
        }

        strong {
            display: block;
            margin-top: 10px;
            color: #576574;
            font-weight: normal;
        }

        .card-content {
            flex-grow: 1;
        }

        .card-footer {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .details-link {
            color: #6a89cc;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
        }

        .details-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 1024px) {
            li {
                width: calc(33.33% - 20px); 
            }
        }

        @media (max-width: 768px) {
            li {
                width: calc(50% - 20px); 
            }
        }

        @media (max-width: 480px) {
            li {
                width: calc(100% - 20px); 
            }
        }
    </style>
</head>
<body>
    <h1>Справочник журналов</h1>

    <div class="controls">
        <a href="logout.php">
            <button type="button" class="logout-button">Выход</button>
        </a>
        <a href="add_journal.php">
            <button type="button" class="add-button">Добавить новый журнал</button>
        </a>
        <a href="authors_list.php">
            <button type="button" class="authors-button">Список авторов</button>
        </a>
        <form method="GET" style="display: inline;">
            <input type="text" name="search" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Поиск по названию">
            <button type="submit">Поиск</button>
        </form>
    </div>

    <ul>
        <?php foreach ($journals as $journal): ?>
            <li>
                <a href="journal_details.php?id=<?= $journal['id'] ?>"><?= htmlspecialchars($journal['title']) ?></a>
                <div class="card-content">
                    <strong>Дата выпуска:</strong> <?= htmlspecialchars($journal['release_date']) ?>
                    <strong>Авторы:</strong>
                    <?php
                    if (isset($authorsByJournal[$journal['id']])) {
                        $authorIds = $authorsByJournal[$journal['id']];
                        foreach ($authorIds as $authorId) {
                            $author = $authorsById[$authorId];
                            echo htmlspecialchars($author['last_name'] . ' ' . $author['first_name'] . ' ' . $author['middle_name']) . '<br>';
                        }
                    }
                    ?>
                    <strong>Короткое описание:</strong> <?= htmlspecialchars($journal['short_description']) ?>
                </div>
                <div class="card-footer">
                    <a href="journal_details.php?id=<?= $journal['id'] ?>" class="details-link">Подробнее</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
