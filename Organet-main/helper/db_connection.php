<?php

define("DB_HOST","127.0.0.1");
define("DB_NAME","organet");
define("DB_USER","root");
define("DB_PASSWORD","");

$mm_conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (!$mm_conn)
{
    echo "connection Problem";
}

