<?php

namespace MF\Controller;

abstract class Action
{

    protected $view;

    public function __construct()
    {
        //Cria um objeto vazio, que pode ser preenchido durante a lógica da aplicação
        $this->view = new \stdClass();
    }

    //Tem a função de realizar os respectivos requires
    protected function render($view, $layout = 'layout'){
        $this->view->page = $view;
    
        //Verificando se existe o Layout
        if(file_exists("../App/Views/".$layout.".phtml")){
            require_once "../App/Views/".$layout.".phtml";
        } else {
            $this->content();
        }
        
    }

    //Renderizar o contúdo dinâmico dentro da Aplicação
    protected function content(){
        $classeAtual = get_class($this);
        $classeAtual = str_replace('App\\Controllers\\','',$classeAtual);
        $classeAtual = strtolower(str_replace('Controller', '', $classeAtual));

        require_once "../App/Views/".$classeAtual."/".$this->view->page.".phtml";
    }
}
