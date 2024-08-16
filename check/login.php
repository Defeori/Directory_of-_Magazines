<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Пожалуйста, заполните оба поля.";
    } else {
        try {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && $password === $user['password']) {  
                $_SESSION['user_id'] = $user['id'];
                header('Location: view_journals.php');
                exit();
            } else {
                $error = "Неверное имя пользователя или пароль.";
            }
        } catch (PDOException $e) {
            $error = "Ошибка базы данных: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма входа</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Candara', sans-serif;
            background: linear-gradient(to bottom right, #76b2fe, #b69efe);
        }
        .container {
            text-align: center;
            padding: 40px;
            background-color: #fff;
            border-radius: 20px;
            width: 360px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .container:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }
        h2 {
            font-size: 40px;
            margin-bottom: 20px;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-size: 20px;
            margin: 10px 0 5px;
            text-align: left;
            color: #555;
        }
        input[type="text"], input[type="password"] {
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 20px;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #007BFF;
            outline: none;
        }
        button {
            padding: 12px;
            border: none;
            border-radius: 30px;
            background: linear-gradient(to right, #007BFF, #9013fe);
            color: white;
            font-size: 20px;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s ease;
        }
       
        p {
            margin-top: 20px;
            font-size: 20px;
            color: #555;
        }
        p a {
            color: #00c914;
            text-decoration: none;
            font-size: 20px;
            transition: color 0.3s ease;
        }
        p a:hover {
            color: #9013fe;
        }
        .error {
            color: red;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Вход</h2>
        <?php if (!empty($error)) echo '<p class="error">' . $error . '</p>'; ?>
        <form method="post">
            <label for="username">Логин</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Пароль</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Войти</button>
        </form>
        <p>У вас нет учетной записи? <a href="register.php">Зарегистрироваться</a></p>
    </div>
</body>
</html>
