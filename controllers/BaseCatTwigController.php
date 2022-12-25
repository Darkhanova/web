<?php

class BaseCatTwigController extends TwigBaseController {
    public function getContext(): array
    {
        $context = parent::getContext();

        $query = $this->pdo->query("SELECT type FROM types ORDER BY 1");
        $types = $query->fetchAll();
        $context['types'] = $types;

        $url = $_SERVER['REQUEST_URI'];;


		if (!isset($_SESSION['urls'])){
			$_SESSION['urls'] = [];
		}

        if (sizeof($_SESSION['urls']) > 9) {
            array_shift($_SESSION['urls']);
        }

		array_push($_SESSION['urls'], $url);



        $context['urls'] = $_SESSION['urls'];

        $context['is_logged'] = isset($_SESSION['is_logged']) ? $_SESSION['is_logged'] : false;

        return $context;
    }
}