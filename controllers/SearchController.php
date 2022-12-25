<?php
require_once "../controllers/BaseCatTwigController.php";

class SearchController extends BaseCatTwigController
{
    public $template = "search.twig";

    public function getContext(): array
    {
        $context = parent::getContext();

        $type = isset($_GET['type']) ? $_GET['type'] : '';
        $title = isset($_GET['title']) ? $_GET['title'] : '';


        $sql = <<<EOL
SELECT id, title
FROM cats_objects
WHERE (:title = '' OR title like CONCAT('%', :title, '%'))
        AND (:type = '' OR type like CONCAT('%', :type, '%'))
EOL;

        $query = $this->pdo->prepare($sql);

        $query->bindValue("title", $title);
        $query->bindValue("type", $type);
        $query->execute();

        $context['type'] = $type;
        $context['title'] = $title;

        $context['cats'] = $query->fetchAll();

        return $context;
    }
}
