<?php
class Pagination {
    private $total;
    private $index;
    private $size;
    private $segment;

    public function  __construct($total, $segment, $index, $size) {
        $this->total = $total;
        $this->segment = $segment;
        $this->index = $index;
        $this->size = $size;
    }

    public function getPages(){
        $pages = array();
        $startIndex = 1;
        $endIndex = $this->size;
        $totalPages = 0;
        if($this->total % $this->segment > 0){
            $totalPages = ($this->total - ($this->total % $this->segment)) / $this->segment;
            $totalPages++;
        }
        else{
            $totalPages = $this->total / $this->segment;
        }
        if($totalPages <= $this->size){
            $endIndex = $totalPages;
        }
        else {
            $half = ceil($this->size / $this->index);
            if($this->index > $half || $this->index > $this->size){
                $startIndex = $this->index - $half;
                $endIndex = $this->index + $half;
                if($endIndex > $totalPages){
                    $endIndex = $totalPages;
                    $startIndex = $endIndex - $this->size + 1;
                }
                $startIndex = $endIndex - $this->size + 1;
            }
        }

        for($i = $startIndex; $i <= $endIndex; $i++){
            $pages[] = $i;
        }
        return $pages;
    }
    
}
?>
