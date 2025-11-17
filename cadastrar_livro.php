<?php
require 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
    $ano = (int) $_POST['ano'];

    // Upload da imagem
    $caminhoImagem = null;
    if (!empty($_FILES['capa']['name'])) {
        $nomeArquivo = time() . '_' . basename($_FILES['capa']['name']);
        $destino = 'uploads/' . $nomeArquivo;

        // Verifica se é imagem
        $extensao = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));
        $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($extensao, $tiposPermitidos)) {
            if (move_uploaded_file($_FILES['capa']['tmp_name'], $destino)) {
                $caminhoImagem = $destino;
            } else {
                echo "<script>alert('Erro ao enviar a imagem!');</script>";
            }
        } else {
            echo "<script>alert('Formato de imagem não permitido!');</script>";
        }
    }

    // Insere no banco
    $sql = "INSERT INTO livros (titulo, autor, ano_publicacao, capa) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$titulo, $autor, $ano, $caminhoImagem]);

    echo "<script>alert('Livro cadastrado com sucesso!'); window.location='listar_livros.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Cadastrar Livro</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 30px;
        background: #f8f9fa;
    }
    form {
        background: white;
        padding: 20px;
        border-radius: 10px;
        width: 400px;
        margin: auto;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    input, button {
        width: 100%;
        padding: 10px;
        margin: 8px 0;
    }
    button {
        background: #667eea;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }
    button:hover {
        background: #556cd6;
    }
</style>
</head>
<body>

<h2 style="text-align:center;">Cadastrar Livro</h2>
<form method="POST" enctype="multipart/form-data">
    <label>Título:</label>
    <input type="text" name="titulo" required>
    
    <label>Autor:</label>
    <input type="text" name="autor" required>
    
    <label>Ano de Publicação:</label>
    <input type="number" name="ano" min="1000" max="2099" required>
    
    <label>Capa do Livro:</label>
    <input type="file" name="capa" accept="image/*">

    <button type="submit">Cadastrar</button>
</form>

</body>
</html>
