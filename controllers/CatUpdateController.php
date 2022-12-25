<?php
require_once "BaseCatTwigController.php";

class CatUpdateController extends BaseCatTwigController
{
	public $template = "cat_update.twig";

	public function get(array $context) // добавили параметр
	{
		$id = $this->params['id'];
		$sql = <<<EOL
SELECT * FROM cats_objects WHERE id=:id
EOL;
		// подготавливаем запрос к БД
		$query = $this->pdo->prepare($sql);
		// привязываем параметры
		$query->bindValue("id", $id);
		// выполняем запрос
		$query->execute();

		$data = $query->fetch();

		$context['object'] = $data;


		parent::get($context);

	}

	public function post(array $context) { // добавили параметр

		$name = $_POST['title'];
		$type = $_POST['type'];
		$info = $_POST['info'];


		$sql = <<<EOL
		UPDATE cats_objects
		SET title = :name, type = :type, info = :info
		WHERE id = :id
		EOL;

		$query = $this->pdo->prepare($sql);
		$query->bindValue("name", $name);
		$query->bindValue("type", $type);
		$query->bindValue("info", $info);
		$query->bindValue("id", $this->params['id']);

		$query->execute();

		$context['message'] = 'Успешное изменение';
		$context['id'] = $this->params['id'];

		$this->get($context);
	}
}
