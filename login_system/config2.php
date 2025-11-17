<?php
$host = "localhost";
$usuario = "root";      // usuário padrão do XAMPP/WAMP
$senha = "";            // deixe vazio se não houver senha
$banco = "sistema_login";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>