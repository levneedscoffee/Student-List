<?php
namespace StudentList\Controllers;



class Controller
{
    private $twig;

    protected function twigRender($templates, $values=array())
    {
        $loader = new \Twig_Loader_Filesystem('/var/www/project/app/view');
        $this->twig = new \Twig_Environment($loader);

        $this->addNewFunction();

        echo $this->twig->render($templates, $values);
    }
    private function addNewFunction()
    {
        $func = new \Twig_Function('getLink',
            function ($page, $sort = false){

            $constructLink = ["page"=>$page];

            if($sort){
                $sort = $sort;// Если аргумент $sort передан то будет использован аргумент, а не $_GET["sort"]
            }elseif(isset($_GET['sort'])){
                $sort = $_GET["sort"];
            }
            $search = isset($_GET["search"]) ? $_GET['search'] : false;


            if($search){
                $constructLink["search"] = $search;
            }
            if($sort){
                $constructLink["sort"] = $sort;
            }

                $link = "main?" . http_build_query($constructLink);

                return $link;
            });

        $this->twig->addFunction($func);
    }

}


