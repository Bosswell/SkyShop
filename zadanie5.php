<?php

$arr = array(
    'Komputery > Laptopy > Akcesoria > Torby',
    'Komputery > Laptopy > Myszki',
    'Monitory > LCD > 15',
    'Komputery > Stacjonarne > Dell',
    'Pozostale'
);

$arrObj = new ArrayObject();

function filter_arr(array $arr, string $char) {
    $result = [];

    foreach(array_flip($arr) as $path => $value) {
        $temp = &$result;

        foreach(explode($char, $path) as $key) {
            $temp =& $temp[trim($key)];
        }
        $temp = [];
    }

    return $result;
}


echo '<pre>';
print_r(filter_arr($arr, '>'));
echo '</pre>';