<?php
require 'config.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM authors WHERE CONCAT_WS(' ', first_name, last_name, middle_name) LIKE :search ORDER BY last_name ASC";
$authorsStmt = $pdo->prepare($sql);
$authorsStmt->execute(['search' => "%$search%"]);
$authors = $authorsStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_author'])) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $middleName = $_POST['middle_name'];

    if ($firstName && $lastName) {
        $stmt = $pdo->prepare("INSERT INTO authors (first_name, last_name, middle_name) VALUES (?, ?, ?)");
        $stmt->execute([$firstName, $lastName, $middleName]);
        header("Location: " . $_SERVER['PHP_SELF']); 
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список авторов</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding: 0;
            color: #333;
            background: linear-gradient(to bottom right, #76b2fe, #b69efe);
        }

        h1 {
            text-align: center;
            color: white;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
            font-size: 2.5em;
            letter-spacing: 0.5px;
            margin: 20px 0;
        }

        .controls {
            text-align: center;
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .controls > * {
            display: flex;
            align-items: center;
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

        .back-button {
            background-color: #38ada9;
        }

        .back-button:hover {
            background-color: #3c6382;
        }

        .add-button {
            background-color: #f39c12;
        }

        .add-button:hover {
            background-color: #e67e22;
        }

        .add-author-form {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: none; 
        }

        .add-author-form input {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #dfe3e6;
            border-radius: 5px;
        }

        .add-author-form button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #4a90e2;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-author-form button:hover {
            background-color: #3b7dd8;
        }

        .author-list {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .author-card {
            background: #f4f7fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            align-items: center;
        }

        .author-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .author-card .author-name {
            font-size: 18px;
            font-weight: 500;
            color: #333;
            margin: 0;
        }

        .search-form {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center; 
        }

        .search-form input[type="text"] {
            padding: 12px 18px;
            width: 300px;
            border-radius: 25px;
            border: none;
            font-size: 16px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        .search-form input[type="text"]:focus {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            outline: none;
        }

        .search-form button {
            padding: 12px 20px;
            background-color: #6a89cc;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .search-form button:hover {
            background-color: #4a69bd;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }

       
        .controls a {
            text-decoration: none;
        }

        .scroll-top-button {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .scroll-top-button button {
            padding: 12px 20px;
            background-color: #6a89cc;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .scroll-top-button button:hover {
            background-color: #4a69bd;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }

    </style>
</head>
<body>

    <h1>Список авторов</h1>

    <div class="controls">
        <a href="view_journals.php">
            <button type="button" class="back-button">Вернуться к списку журналов</button>
        </a>
        <button class="add-button" onclick="toggleForm()">Добавить нового автора</button>
        <form method="get" class="search-form">
            <input type="text" name="search" placeholder="Поиск по автору" value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit">Поиск</button>
        </form>
    </div>

    <form method="post" class="add-author-form">
        <h2>Добавить нового автора</h2>
        <input type="text" name="first_name" placeholder="Имя" required>
        <input type="text" name="last_name" placeholder="Фамилия" required>
        <input type="text" name="middle_name" placeholder="Отчество">
        <button type="submit" name="add_author">Добавить</button>
    </form>

    <div class="author-list">
        <?php foreach ($authors as $author): ?>
            <div class="author-card">
                <p class="author-name"><?= htmlspecialchars($author['last_name'] . ' ' . $author['first_name'] . ' ' . $author['middle_name']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="scroll-top-button">
        <button onclick="scrollToTop()">Наверх</button>
    </div>

    <script>
        function toggleForm() {
            const form = document.querySelector('.add-author-form');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</body>
</html>
