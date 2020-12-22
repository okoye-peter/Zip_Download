<?php

use controllers\Controller;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST,OPTIONS,DELETE,PATCH");
header("Access-Control-Allow-Headers: Host,Connection,Accept,Authorization,Content-type,X-Request-With,User-Agent,Referer,Methods");

function autoload($classname)
{
    $classname = str_replace('\\','/',$classname);
    require $classname.'.php';
}
spl_autoload_register('autoload');
// echo json_encode(['success'=>'this is a success message'], true);
$a= new Controller;
if ($_SERVER['REQUEST_METHOD']== 'GET' && $_GET['action'] && $_GET['action'] == 'fetch') {
    echo $a->fetchFiles();    
}elseif($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['action'] && $_GET['action'] == 'upload'){
    echo $a->upload($_FILES);
}elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['action'] && $_GET['action'] == 'delete') {
    echo $a->delete($_POST);
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['action'] && $_GET['action'] == 'download') {
    $a->ZipFiles();
}