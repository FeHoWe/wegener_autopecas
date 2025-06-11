<?php
session_start(); // Apenas uma vez no topo

// Conexão com o banco de dados
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "wegener_autopecas";

$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Recebe e sanitiza o dado do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_categoria = trim($_POST['nome_categoria']);

    $stmt = $conn->prepare("INSERT INTO categoria (nome_categoria) VALUES (?)");
    $stmt->bind_param("s", $nome_categoria);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Categoria adicionada com sucesso!";
        $_SESSION['mensagem_tipo'] = "success"; // Tipo de mensagem para Bootstrap
    } else {
        $_SESSION['mensagem'] = "Erro ao adicionar categoria: " . $stmt->error;
        $_SESSION['mensagem_tipo'] = "danger"; // Tipo de mensagem para Bootstrap
    }

    $stmt->close();
    $conn->close();

    // Redireciona para evitar reenvio do formulário ao atualizar
    header("Location: adicionar_categoria.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Categoria</title>
    <!-- Link para o Bootstrap 4.5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="adicionar_categoria.css">
</head>
<body>

    <div class="container mt-5">
        <h1>Adicionar Nova Categoria</h1>

        <?php
        // Verifica se há mensagem para exibir
        if (isset($_SESSION['mensagem'])) {
            $mensagem_tipo = $_SESSION['mensagem_tipo'] ?? 'info'; // Padrão: 'info' se não definido
            echo '<div class="alert alert-' . $mensagem_tipo . '">' . $_SESSION['mensagem'] . '</div>';
            unset($_SESSION['mensagem']);
            unset($_SESSION['mensagem_tipo']);
        }
        ?>

        <form action="adicionar_categoria.php" method="POST">
            <div class="form-group">
                <label for="nome_categoria">Nome da Categoria:</label>
                <input type="text" id="nome_categoria" name="nome_categoria" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar</button>
        </form>

        <a href="home.php" class="btn btn-secondary mt-3">Voltar para Home</a>
    </div>

    <!-- Script para o Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
