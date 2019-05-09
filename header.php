<?php
function print_head($page_title="Ravatae | 國立東華大學學生會電子投票系統", $page_lang = "zh_TW"){
    echo (
        "<!DOCTYPE html>
        <html lang='$page_lang'>
        <head>
            <meta charset = 'UTF-8'>
            <link rel = 'stylesheet' href = 'css/main.css'>
            <link rel = 'stylesheet' href = 'css/pure-min.css'>
            <meta name = 'viewport' content = 'width=device-width, initial-scale=1'>
            <title>$page_title</title>
        </head>"
    );
}
?>