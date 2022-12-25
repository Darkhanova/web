<?php

use function PHPSTORM_META\type;

require_once "BaseCatTwigController.php";

class CatTypeCreateController extends BaseCatTwigController
{
    public $template = "cat_type_create.twig";

    public function get(array $context) // добавили параметр
    {
        parent::get($context); // пробросили параметр
    }

    public function post(array $context)
    { // добавили параметр
        $type = $_POST['type'];

        $tmp_name = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        move_uploaded_file($tmp_name, "../public/media/$image_name");
        $image_url = "/media/$image_name";

        // создаем текст запрос
        $sql = <<<EOL
INSERT INTO types(type, image)
VALUES(:type, :image_url)
EOL;

        // подготавливаем запрос к БД
        $query = $this->pdo->prepare($sql);
        $query->bindValue('type', $type);
        $query->bindValue('image_url', $image_url);

        // выполняем запрос
        $query->execute();

        $context['message'] = 'Вы успешно создали объект';
        $context['id'] = $this->pdo->lastInsertId(); // получаем id нового добавленного объекта

        $query = $this->pdo->query("SELECT * FROM types");
        $context['types'] = $query->fetchAll();

        $this->get($context);
    }
}
