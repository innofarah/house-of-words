<?php

session_start();
include 'functions.php';
include "db_connection.php";

generate_updates_view($db);
?>