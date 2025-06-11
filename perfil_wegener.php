<?php
session_start();

// Redireciona para login se não estiver logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login_wegener.php");
    exit;
}

$usuarioId = $_SESSION['usuario_id'];
$nomeUsuario = $_SESSION['usuario'];

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "wegener_autopecas";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$sql = "SELECT nome, email FROM usuarios_wegener WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$result = $stmt->get_result();
$dadosUsuario = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="container my-5">
    <div class="card mx-auto shadow" style="max-width: 500px;">
        <div class="card-body text-center">
            <i class="bi bi-person-circle" style="font-size: 4rem;"></i>
            <h3 class="card-title mt-3"><?= htmlspecialchars($dadosUsuario['nome']) ?></h3>
            <p class="card-text text-muted"><?= htmlspecialchars($dadosUsuario['email']) ?></p>

            <a href="home.php" class="btn btn-outline-dark mt-2"><i class="bi bi-box-arrow-right"></i>Ínicio</a>
            <a href="editar_perfil.php" class="btn btn-outline-primary mt-3"><i class="bi bi-pencil"></i> Editar Perfil</a>
            <a href="logout_wegener.php" class="btn btn-outline-danger mt-2"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </div>
    </div>
</div>
</body>
</html>
