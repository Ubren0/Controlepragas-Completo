<?php
// conexao.php

$host = "localhost";
$dbname = "greensea";
$user = "root";
$pass = "usbw";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão com o banco: " . $e->getMessage());
}
