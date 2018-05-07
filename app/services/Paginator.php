<?php
namespace StudentList\Services;
use StudentList\Models\DatabaseMySql;
use StudentList\Models\StudentDataGateway;

class Paginator
{
    private $limit;
    private $colButton = 7;
    private $search = false;

    public function __construct($limit=20)
    {
        $this->limit = $limit;
    }
    public function returnPageValues($page, $search=null, $sort = null)
    {
        $page = (($page-1) * $this->limit);
        $sort = ($sort != null) ? $sort : 'name';
        $this->search = ($search != null) ? $search : false;


        $db = new StudentDataGateway(new DatabaseMySql());
        if($this->search){
            $values = $db->returnSearchLimitData($page, $this->limit, $search, $sort);
        }else{
            $values = $db->returnLimitData($page, $this->limit, $sort);
        }

        return $values;
    }
    public function returnCountPage()
    {
        $db = new StudentDataGateway(new DatabaseMySql());

        if($this->search){
            return ceil($db->countSearchPage($this->search)/$this->limit);
        }else{
            return ceil($db->countPage()/$this->limit);
        }

    }
    public function returnColButton()
    {
        $countPage = $this->returnCountPage();
        $this->colButton = ($countPage > $this->colButton) ? $this->colButton : $countPage;
        return $this->colButton;
    }
    public function returnIterableArr($pageNum, $colButton = 7)
    {
        $countPage = $this->returnCountPage();
        $ras = ceil($colButton/2);
        $this->colButton = ($countPage > $colButton) ? $colButton : $countPage;
        $last = $countPage - $colButton;

        if($countPage <= 1 ){
            return 0;
        }elseif($this->colButton === $countPage){
            return range(1,$countPage);
        }elseif ($pageNum < $this->colButton){
            return range(1,$this->colButton);
        }elseif($pageNum >= $this->colButton && $pageNum <= $last){
            return range($pageNum-$ras, $pageNum+$ras);
        }elseif ($pageNum >= $last){
            return range( $last, $countPage);
        }

    }

}