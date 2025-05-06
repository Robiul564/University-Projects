<?php
$_SESSION['id']=0;
session_destroy();
header("Location: ../index.php");
