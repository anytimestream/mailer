<?php
$url = strtok($_SERVER['REQUEST_URI'], "?");

$url_hash_table = array();
$url_hash_table[1] = 'a';
$url_hash_table[2] = 'b';
$url_hash_table[3] = 'c';
$url_hash_table[4] = 'd';
$url_hash_table[5] = 'e';
$url_hash_table[6] = 'f';
$url_hash_table[7] = 'g';
$url_hash_table[8] = 'h';
$url_hash_table[9] = 'i';

$paths = explode('/', $url);
for ($i = 0; $i < count($paths); $i++) {
    if (isset($url_hash_table[$i])) {
        if (strpos($paths[$i], '?') > 0 && strpos($paths[$i], '.php') == false) {
            $paths[$i] = substr($paths[$i], 0, strpos($paths[$i], '?'));
        }
        if (strlen($paths[$i]) > 0 && substr($paths[$i], 0, 1) != '?' && strpos($paths[$i], '.php') == false) {
            if (strpos($paths[$i], '.htm') != false) {
                $paths[$i] = substr($paths[$i], 0, strpos($paths[$i], '.htm'));
            }
            $_GET[$url_hash_table[$i]] = $paths[$i];
        }
    }
}

function getCurrentTab($source, $target, $isDefault) {
    if (isset($_GET[$source]) && $_GET[$source] == $target) {
        return "active";
    } else if (!isset($_GET[$source]) && $isDefault == true) {
        return "active";
    } else {
        return 'default';
    }
}
?>
