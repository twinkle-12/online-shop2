<?php

function validateLoginForm(array $methodPost): array
{
    $errors = [];

    if (isset($methodPost['email'])) {
        $email = $methodPost['email'];
        if (empty($email)) {
            $errors['email'] = 'Поле email должно быть заполнено';
        } elseif (strlen($email) < 3) {
            $errors['email'] = 'Email слишком короткий';
        }
    }

    if (isset($methodPost['password'])) {
        $password = $methodPost['password'];
        if (empty($password)) {
            $errors['password'] = 'Пароль не может быть пустым';
        } elseif (strlen($password) < 8) {
            $errors['password'] = 'Пароль слишком короткий';
        }
    }
    return $errors;
}

$errors = validateLoginForm($_POST);

if (empty($errors)) {
    $email= $_POST['email'];
    $password = $_POST['password'];

    $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'user', 'pwd');
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");

    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user === false) {
        $errors['email'] = 'Email или пароль не корректный';
    } elseif (password_verify($password, $user['password'])) {
//            setcookie('user_id', $data['id']);
        session_start();
        $_SESSION['user_id'] = $user['id'];
        header('Location: /catalog.php');
    } else {
        $errors['password'] = 'Email или пароль не корректный';
    }
}
require_once './login_form.php';
