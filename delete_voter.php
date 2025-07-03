<?php
session_start();
require 'db.php';
$id = $_GET['id'];
$pdo->prepare("DELETE FROM voters WHERE id = ?")->execute([$id]);
header('Location: voters.php');
?>