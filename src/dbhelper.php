<?php
    $username = $_POST['username'];
    $password = $_POST['password'];

    $host = 'localhost';
    $port = 8000;
    $username = stripcslashes($username);
    $password = stripcslashes($password);
    $username = pg_escape_string($username);
    $password = pg_escape_string($password);
    $database = 'somedb';

    //connect to db
    $dbc = pg_connect("host=" . $host . " port=" . $port . " dbname=" . $database . " user=" . $username . " password=" . $password);

?>
