<?php

class SendMailErrorService {

    public static function GetErrors() {
        try {
            $size = 20;
            $index = 1;
            $orderBy = "creation_date desc";
            $values = array();
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            if (isset($_GET['order_by'])) {
                $orderBy = $_GET['order_by'];
            }
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('SendMailError');
            $sql = "select * from " . SendMailError::GetDSN() . " order by " . $orderBy;
            $csql = 'select count(*) from ' . SendMailError::GetDSN();
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new DataPagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/send-mail-errors?');
            $pagination->setPages();
            $_GET['send-mail-errors'] = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $pagination->setPageCount($_GET['send-mail-errors']->count());
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }
    
    public static function GetErrorById($id) {
        try {

            $pm = PersistenceManager::getConnection();

            $sendMailError = $pm->getObjectById('SendMailError', $id);
            if ($sendMailError != null) {
                $_GET['send-mail-error'] = $sendMailError;
            } else {
                $_GET['msg'] = 'Error not found';
            }
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function DoDelete($id) {
        try {

            $pm = PersistenceManager::getConnection();

            $sendMailError = $pm->getObjectById('SendMailError', $id);
            if ($sendMailError != null) {
                $pm->deleteObjectById('SendMailError', $id);
                header('location: ' . CONTEXT_PATH . '/backend/send-mail-errors');
            } else {
                $_GET['msg'] = 'Error not found';
            }
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

}
