<?php

$limit = 100;

ob_start();

for ($i = 0; $i < $limit; $i++) {
    $rendered = false;

    if ($i % 3 === 0) {
        $rendered = true;

        echo 'Good -> ' . $i;
    }

    if ($i % 5 === 0) {
        $rendered = true;

        echo 'Excellent -> ' . $i;
    }

    if (!$rendered) {
        echo 'Ok -> ' . $i;
    }

    echo '<br/>';

    if ($i === $limit - 10) {
        echo 'Stop!';
        break;
    }
}

$output = ob_get_contents();

$stream = fopen(
    (new DateTime())->format('Y-m-d__H_i_s') . '.txt',
    'w+'
);

fwrite($stream, preg_replace("/\<br\/\>/", "\n", $output));