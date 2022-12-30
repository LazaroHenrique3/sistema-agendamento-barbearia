<?php

namespace App\Controllers;

use MF\Controller\Action;

class AppController extends Action
{

    public function dashboard()
    {
        AuthController::validaAutenticacao();
        $this->render('dashboard', 'layout2');
    }
}
