<?php
// header("Content-Type: text/plain");

function complexStrtolower($regex, $value) {
    return preg_replace(
        '/(' . $regex . ')/i',
        'strtolower("\\1")',
        $value
    );
}

foreach ($_GET as $regex => $value) {
    echo complexStrtolower($regex, $value) . "\n";
}
