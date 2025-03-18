<?php

function validateRegistrationForm(array $methodPost): array
{
    $errors = [];

    if (isset($methodPost['name'])) {
        $name = $methodPost['name'];
        if (empty($name)) {
            $errors['name'] = 'Имя не может быть пустым';
        } elseif (strlen($name) < 3) {
            $errors['name'] = 'Имя слишком короткое';
        }
    } else {
        $errors['name'] = 'Требуется имя';
    }

    if (isset($methodPost['email'])) {
        $email = $methodPost['email'];
        if (empty($email)) {
            $errors['email'] = 'Email не может быть пустым';
        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Email некорректный';
        }
    } else {
        $errors['email'] = 'Требуется email';
    }

    if (isset($methodPost['psw'])) {
        $password = $methodPost['psw'];
        if (empty($password)) {
            $errors['password'] = 'Пароль не может быть пустым';
        } elseif (strlen($password) < 8) {
            $errors['password'] = 'Пароль слишком короткий';
        } elseif (!preg_match("#[0-9]+#", $password)) {
            $errors['password'] = 'Пароль должен содержать хотя бы одну цифру';
        } elseif (!preg_match("#[\W]+#", $password)) {
            $errors['password'] = 'Пароль должен содержать один специальный символ';
        }
    }

    if (isset($methodPost['psw-repeat'])) {
        $passwordRep = $methodPost['psw-repeat'];
        if ($password !== $passwordRep) {
            $errors['psw-repeat'] = 'Пароль не совпадает';
        }
    }
    return $errors;
}

$errors = validateRegistrationForm($_POST);

if (empty($errors)) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['psw'];
    $passwordRep = $_POST['psw-repeat'];

    $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'user', 'pwd');
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt->execute(['name' => $name, 'email' => $email, 'password' => $hash]);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);

    print_r($stmt->fetch());
} else {
    require_once './registration_form.php';
}



