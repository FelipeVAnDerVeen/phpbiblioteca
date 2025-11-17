<?php
session_start();
require 'config2.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        header("Location: /Biblioteca/index.php");
        exit;
    } else {
        $erro = "Email ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Login - Biblioteca</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #667eea, #764ba2);
        height: 100vh;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-container {
        background: #fff;
        padding: 40px 30px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        width: 100%;
        max-width: 400px;
        text-align: center;
    }

    h2 {
        margin-bottom: 20px;
        color: #333;
    }

    input[type="email"],
    input[type="password"] {
        width: 90%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 8px;
        outline: none;
        transition: border 0.3s;
    }

    input[type="email"]:focus,
    input[type="password"]:focus {
        border-color: #667eea;
    }

    button {
        width: 95%;
        background: #667eea;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 15px;
        font-size: 16px;
        transition: background 0.3s;
    }

    button:hover {
        background: #5a6fe0;
    }

    p {
        margin-top: 15px;
        font-size: 14px;
    }

    a {
        color: #667eea;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    .erro {
        background: #ffe0e0;
        color: #b30000;
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 15px;
        font-size: 14px;
    }
</style>
</head>
<body>

<div class="login-container">
    <h2>Login - Biblioteca</h2>

    <?php if (!empty($erro)) { ?>
        <div class="erro"><?php echo $erro; ?></div>
    <?php } ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Digite seu e-mail" required><br>
        <input type="password" name="senha" placeholder="Digite sua senha" required><br>
        <button type="submit">Entrar</button>
    </form>

    <p>Não tem conta? <a href="register.php">Cadastre-se</a></p>
</div>

</body>
</html>
