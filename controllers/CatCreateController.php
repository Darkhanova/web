<?php
require_once "BaseCatTwigController.php";

class CatCreateController extends BaseCatTwigController {
    public $template = "cat_create.twig";

    public function get(array $context) // добавили параметр
    {
        // echo $_SERVER['REQUEST_METHOD'];

        parent::get($context); // пробросили параметр
    }

    public function post(array $context) { // добавили параметр
        $title = $_POST['title'];
        $type = $_POST['type'];
        $info = $_POST['info'];

        $tmp_name = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        move_uploaded_file($tmp_name, "../public/image/$image_name");
        $image_url = "/image/$image_name";

        // создаем текст запрос
        $sql = <<<EOL
INSERT INTO cats_objects(title, type, info, image)
VALUES(:title, :type, :info, :image_url)
EOL;

        // подготавливаем запрос к БД
        $query = $this->pdo->prepare($sql);
        // привязываем параметры
        $query->bindValue("title", $title);
        $query->bindValue("type", $type);
        $query->bindValue("info", $info);
        $query->bindValue("image_url", $image_url);

        // выполняем запрос
        $query->execute();

        $context['message'] = 'Вы успешно создали объект';
        $context['id'] = $this->pdo->lastInsertId(); // получаем id нового добавленного объекта

        $this->get($context);
    }
}