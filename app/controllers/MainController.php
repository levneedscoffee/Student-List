<?php
namespace StudentList\Controllers;
use StudentList\Models\Paginator;



class MainController extends Controller
{
    const CONTROLLER_VIEW = 'mainView.html';


    public function actionIndex()
    {
        $search = isset($_GET['search']) ? trim($_GET['search']) : null;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : null;

        $pageNum = $this->returnNumPage();
        $val = $this->pagination($pageNum, $search, $sort);

        if($search !== null){
            $this->wrapSearchStr($val, $search);
        }


        $this->twigRender(self::CONTROLLER_VIEW, array("values" => $val["values"],
            "countPage" => $val["countPage"],
            "arr" => $val["arr"],
            "colButton" => $val["colButton"],
            "pageNum" => $pageNum,
            "search" => $search));
    }

    private function pagination($pageNum, $search, $sort)
    {
        $paginator = new Paginator(10);
        $values = $paginator->returnPageValues($pageNum, $search, $sort);
        $arr = $paginator->returnIterableArr($pageNum);
        $countPage = $paginator->returnCountPage();
        $colButton = $paginator->returnColButton();

        return array("values" => $values,
            "arr" => $arr,
            "countPage" => $countPage,
            "colButton" => $colButton);

    }

    private function returnNumPage()
    {
        return (isset($_GET["page"])) ? $_GET["page"] : 1;
    }

    private function wrapSearchStr(&$val, $search)
    {
        foreach ($val["values"] as &$page) {
            foreach ($page as &$str) {
              $str = preg_replace('/(' .$search.')/iu', '<b>$1</b>', $str);
            }
        }
        unset($page);
        unset($str);
    }
}

