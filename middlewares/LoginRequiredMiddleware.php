<?php

class LoginRequiredMiddleware extends BaseMiddleware{

	public function apply(BaseController $controller, array $context)
    {
      $is_logged = $_SESSION['is_logged'];
      if (!$is_logged) {
          header("Location: /");
          exit;
      }
    }

}