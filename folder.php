<?php

function feedback404() {
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found";
}

$filename = "list.txt";
$lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $target_string) {
    $target_string = strtoupper($target_string);
    $BRAND = strtoupper($target_string);

    $folderPath = __DIR__ . "/$BRAND";
    $filePath = $folderPath . "/index.php";

        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        
        $dir = rtrim(dirname($_SERVER['PHP_SELF']), '/');
    
        $urlPath = "$protocol://$host$dir/$target_string";

    if (!is_dir($folderPath)) {
        if (!mkdir($folderPath, 0777, true)) {
            error_log("Failed to create directory: $folderPath");
            continue;
        }
    }

    ob_start(); 
    include 'template.php';
    $html_content = ob_get_clean(); 

    if (file_put_contents($filePath, $html_content) === false) {
       
        error_log("Failed to write file: $filePath");
    }
}

date_default_timezone_set('Asia/Jakarta');
$currentTime = date('Y-m-d\TH:i:sP');
echo "FILES DONE CREATED!";
?>
