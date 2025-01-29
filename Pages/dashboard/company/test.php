<?php
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$company_id = $_SESSION['company_id'] ?? null;
$role = $_SESSION['role'] ?? null;



echo $role;