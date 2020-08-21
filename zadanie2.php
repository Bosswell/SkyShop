<?php

$str = 'Hello 12 worl02d o2';

function getDigitsFromString(string $str) {
    preg_match_all('/\d+/', $str, $matches);

    return implode($matches[0] ?? []);
}

// Expected output 12022
echo getDigitsFromString($str);