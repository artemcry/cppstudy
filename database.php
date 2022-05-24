<?php
// for locahost:
$link = mysqli_connect('localhost','root', '', 'lab_db');

// for host:
//$link = mysqli_connect('sql102.chost.ga','aooss_31753874', 'Xw4Pj8pcuYiLWKy', 'aooss_31753874_cppstudy');

$link->set_charset("utf8mb4");
if(mysqli_connect_errno()) {
    echo('error'. mysqli_connect_error());
    exit();
}
