<?php

function convertErrorToException($e, $str) {
    if ($str != null) {
        throw new Exception($str);
    } else {
        echo $e->getMessage();
    }
}

class AssertProperties {

    private $error = "";

    public function addProperty($type, $property) {
        if (!isset($type[$property])) {
            $this->error .= 'Undefined Input: ' . $property . '<br/>';
        }
    }

    public function assert() {
        if (strlen($this->error) > 0) {
            throw new Exception($this->error);
        }
    }

}
?>
