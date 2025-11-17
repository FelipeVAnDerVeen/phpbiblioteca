<?php
/**
 * Salvar Livro no Banco de Dados
 * 
 * Recebe os dados do formulário de novo livro e realiza
 * a inserção no banco de dados, incluindo upload da capa.
 * 
 * @author Módulo 5
 * @version 1.0
 */

require_once 'config/database.php';
require_once 'config/config.php';
require_once 'includes/funcoes.php';
require_once 'includes/header.php';

$db = Database::getInstance();
$pdo = $db->getConnection();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // ==============================
        // 1️⃣ Coleta dos dados do formulário
        // ==============================
        $titulo = trim($_POST['titulo']);
        $autor_id = (int) $_POST['autor_id'];
        $isbn = trim($_POST['isbn'] ?? '');
        $ano_publicacao = trim($_POST['ano_publicacao'] ?? null);
        $editora = trim($_POST['editora'] ?? '');
        $numero_paginas = trim($_POST['numero_paginas'] ?? null);
        $quantidade_total = (int) ($_POST['quantidade_total'] ?? 1);
        $quantidade_disponivel = (int) ($_POST['quantidade_disponivel'] ?? 1);
        $categoria = trim($_POST['categoria'] ?? '');
        $localizacao = trim($_POST['localizacao'] ?? '');

        // ==============================
        // 2️⃣ Upload da capa
        // ==============================
        $pastaUploads = "uploads/livros/";

        // Garante que a pasta exista
        if (!is_dir($pastaUploads)) {
            mkdir($pastaUploads, 0777, true);
        }

        $caminhoCapa = null;

        if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
            $extensao = strtolower(pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION));
            $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($extensao, $permitidas)) {
                $novoNome = uniqid('capa_', true) . '.' . $extensao;
                $destino = $pastaUploads . $novoNome;

                if (move_uploaded_file($_FILES['capa']['tmp_name'], $destino)) {
                    $caminhoCapa = $destino;
                } else {
                    exibirMensagem('erro', 'Erro ao mover a imagem da capa para o servidor.');
                }
            } else {
                exibirMensagem('erro', 'Formato de imagem inválido. Use JPG, PNG ou GIF.');
            }
        }

        // ==============================
        // 3️⃣ Validações básicas
        // ==============================
        if (empty($titulo)) {
            exibirMensagem('erro', 'O campo "Título" é obrigatório.');
            exit;
        }

        if (empty($autor_id)) {
            exibirMensagem('erro', 'Selecione um autor válido.');
            exit;
        }

        if ($quantidade_disponivel > $quantidade_total) {
            exibirMensagem('erro', 'A quantidade disponível não pode ser maior que a total.');
            exit;
        }

        // ==============================
        // 4️⃣ Inserção no banco de dados
        // ==============================
        $sql = "INSERT INTO livros (
                    titulo, autor_id, isbn, ano_publicacao, editora,
                    numero_paginas, capa, quantidade_total, quantidade_disponivel,
                    categoria, localizacao
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $titulo,
            $autor_id,
            $isbn,
            $ano_publicacao ?: null,
            $editora,
            $numero_paginas ?: null,
            $caminhoCapa,
            $quantidade_total,
            $quantidade_disponivel,
            $categoria,
            $localizacao
        ]);

        // ==============================
        // 5️⃣ Mensagem de sucesso + redirecionamento
        // ==============================
        exibirMensagem('sucesso', '📚 Livro cadastrado com sucesso!');
        echo "<script>
                setTimeout(() => {
                    window.location.href = 'livros.php';
                }, 1500);
              </script>";

    } else {
        exibirMensagem('erro', 'Requisição inválida.');
    }
} catch (PDOException $e) {
    exibirMensagem('erro', 'Erro ao salvar livro: ' . $e->getMessage());
}

require_once 'includes/footer.php';
?>
