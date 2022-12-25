<?php

class LoginController extends BaseCatTwigController
{

	public $template = "login.twig";

    public function get(array $context) {
        parent::get($context);
    }

    public function post(array $context) {
        $user = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        $query = $this->pdo->prepare("SELECT username, password FROM users WHERE username = :user");
        $query->bindValue("user", $user);
        $query->execute();

        $user_auth_data = $query->fetch();

        $valid_user = $user_auth_data['username'] ?? '';
        $valid_password = $user_auth_data['password'] ?? '';

        if (!$user_auth_data || ($valid_user != $user || $valid_password != $password)) {
            $context['message'] = 'Неверный логин или пароль';
        } else {
            $_SESSION['is_logged'] = true;
            header('Location: http://localhost/');
        }

        $this->get($context);
    }
}
