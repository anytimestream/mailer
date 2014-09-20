<?php

class Date {

    public static function convertToMySqlDate($date) {
        if (strlen($date) < 8 || strlen($date) > 10)
            throw new Exception('Invalid Date Format');
        $array = explode('/', $date);
        if (count($array) != 3)
            throw new Exception('Invalid Date Format');
        return $array[2] . '-' . self::addZero($array[1]) . '-' . self::addZero($array[0]);
    }

    static function addZero($value) {
        if (strlen($value) == 1) {
            return '0' . $value;
        }
        return $value;
    }

    public static function convertFromMySqlDate($date_str) {
        $date = $date_str;
        if (strlen($date_str) > 10)
            $date = substr($date_str, 0, 10);
        $array = explode('-', $date);
        if (count($array) != 3)
            throw new Exception('Invalid Date Format');
        if (strlen($date_str) > 10)
            return $array[2] . '/' . $array[1] . '/' . $array[0] . ' ' . substr($date_str, 10, strlen($date_str));
        else
            return $array[2] . '/' . $array[1] . '/' . $array[0];
    }

}

class Util {

    public static function AddLeadingZeros($value, $len) {
        $pad = '';
        for ($i = strlen($value); $i < $len; $i++) {
            $pad .= '0';
        }
        return $pad . $value;
    }

}

function myErrorHandler($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }

    $message = "My ERROR [$errno] $errstr\n";
    $message .= "  Fatal error on line $errline in file $errfile";
    echo $message;
    //mail("chat4zeal@yahoo.com", "Mailer.apps Error", $message);
    exit;
}

