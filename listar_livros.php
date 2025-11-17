<?php
require 'config/config.php';
$sql = "SELECT * FROM livros ORDER BY titulo";
$stmt = $pdo->query($sql);
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Lista de Livros</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f0f2f5;
        padding: 20px;
    }
    .livros {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    .livro {
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        text-align: center;
    }
    .livro img {
        width: 120px;
        height: 160px;
        object-fit: cover;
        border-radius: 6px;
        margin-bottom: 10px;
    }
</style>
</head>
<body>

<h2>📚 Livros Cadastrados</h2>

<div class="livros">
<?php foreach ($livros as $livro): ?>
    <div class="livro">
        <?php if ($livro['capa']): ?>
            <img src="<?php echo htmlspecialchars($livro['capa']); ?>" alt="Capa do livro">
        <?php else: ?>
            <img src="https://via.placeholder.com/120x160?text=Sem+Capa" alt="Sem capa">
        <?php endif; ?>
        <h4><?php echo htmlspecialchars($livro['titulo']); ?></h4>
        <p><strong>Autor:</strong> <?php echo htmlspecialchars($livro['autor']); ?></p>
        <p><strong>Ano:</strong> <?php echo $livro['ano_publicacao']; ?></p>
    </div>
<?php endforeach; ?>
</div>

</body>
</html>
