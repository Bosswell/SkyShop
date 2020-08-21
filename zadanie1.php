<?php

$a = $_GET['a'] ?? null;
$b = $_GET['b'] ?? null;

if (is_null($a) || is_null($b)) {
    throw new Exception('You need to specify \'a\' and \'b\' parameter in your GET method');
}

$search = preg_quote($b);

if ((bool)preg_match("/$search/", $a)) {
    echo 'The a parameter is contained in the b parameter';
} else {
    echo 'The parameter b is not included in the parameter a';
}