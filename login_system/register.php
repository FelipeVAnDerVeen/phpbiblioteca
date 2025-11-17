<?php
require 'config2.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$nome, $email, $senha]);
        echo "<script>alert('Usuário cadastrado com sucesso!'); window.location='login.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Erro: Email já cadastrado!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Cadastro</title>
</head>
<body>
<h2>Cadastro de Usuário</h2>
<form method="POST">
    <label>Nome:</label><br>
    <input type="text" name="nome" required><br><br>
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>
    <label>Senha:</label><br>
    <input type="password" name="senha" required><br><br>
    <button type="submit">Cadastrar</button>
</form>
<p>Já tem conta? <a href="login.php">Faça login</a></p>
</body>
</html>
