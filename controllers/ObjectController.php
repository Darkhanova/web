<?php
require_once "../controllers/BaseCatTwigController.php";

class ObjectController extends BaseCatTwigController {
    public $template = "__object.twig"; // указываем шаблон
    public $nav = [
        [
            "section_name" => "Картинка",
            "section" => "image"
        ],
        [
            "section_name" => "Описание",
            "section" => "info"
        ],
    ];

    public function chooseTemplate() {
        if (isset($_GET['show'])) {
            if ($_GET['show'] == "image") {
                $this->template = "image.twig";
            } elseif ($_GET['show'] == "info") {
                $this->template = "info.twig";
            }
        }
    }

    public function getContext(): array
    {
        $context = parent::getContext();

        $query_statement = "SELECT title FROM cats_objects WHERE id = :my_id";

        if (isset($_GET['show'])) {
            $context['section'] = $_GET['show'];
            if ($_GET['show'] == "image") {

                $query_statement = "SELECT title, image AS section_data FROM cats_objects WHERE id = :my_id";
            } elseif ($_GET['show'] == "info") {
                $query_statement = "SELECT title, info AS section_data FROM cats_objects WHERE id = :my_id";

            }
        }

        $query = $this->pdo->prepare($query_statement);
        $query->bindValue("my_id", $this->params['id']);
        $query->execute();
        $data = $query->fetch();

        $context['title'] = $data['title'];
        if (isset($data['section_data'])) {
            $context['section_data'] = $data['section_data'];
        }

        $context['id'] = $this->params['id'];
        $context['nav'] = $this->nav;

        $cats_query = $this->pdo->query("SELECT title, id FROM cats_objects");
        $cats_data = $cats_query->fetchAll();

        $cats = [];
        foreach ($cats_data as $item) {
            array_push($cats, [
                "title" => $item['title'],
                "id" => $item['id']
            ]);
        }

        $context['cats'] = $cats;



        $context["my_session_message"] = isset($_SESSION['welcome_message']) ? $_SESSION['welcome_message'] : "";
        $context["messages"] = isset($_SESSION['messages']) ? $_SESSION['messages'] : "";

        return $context;
    }
}