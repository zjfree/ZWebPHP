<?php

if (!empty($_POST))
{
    echo json_encode($_POST);
    exit;
}

$data = file_get_contents("php://input");
if (!empty($data))
{
    echo json_encode($data);
    exit;
}

echo 'NULL';

