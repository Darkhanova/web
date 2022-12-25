<?php

class SetWelcomeController extends BaseController{
	public function get(array $context)
	{
		$_SESSION['welcome_message'] = $_GET['message'];


		if (!isset($_SESSION['messages'])){
			$_SESSION['messages'] = [];
		}


		array_push($_SESSION['messages'], $_GET['message']);

		$url = $_SERVER['HTTP_REFERER'];


		// if (!isset($_SESSION['urls'])){
		// 	$_SESSION['urls'] = [];
		// }

		// array_push($_SESSION['urls'], $url);

		header("Location: $url");

		exit;
	}
}