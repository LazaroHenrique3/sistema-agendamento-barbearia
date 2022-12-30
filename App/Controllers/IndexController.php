<?php 

namespace App\Controllers;

//Recursos do Mini Framework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action{

    //Representam as Action(Métodos)
    public function index() {
        $this->render('index');
    }
}

?>