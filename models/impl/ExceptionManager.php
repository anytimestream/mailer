<?php
class ExceptionManager {
    
    public static function RaiseException($e){
        if(DEBUG_MODE == true){
            $_GET['msg'] = $e->getMessage();
        }
    }
}
