<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login_wegener.php");
    exit;
}

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "wegener_autopecas";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$mensagem = "";
$usuarioId = $_SESSION['usuario_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novoNome = trim($_POST["nome"]);
    $novoEmail = trim($_POST["email"]);
    $novaSenha = $_POST["senha"];
    $senhaHash = null;

    if (!empty($novaSenha)) {
        $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
    }

    if ($senhaHash) {
        $sql = "UPDATE usuarios_wegener SET nome = ?, email = ?, senha = ? WHERE id_user = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $novoNome, $novoEmail, $senhaHash, $usuarioId);
    } else {
        $sql = "UPDATE usuarios_wegener SET nome = ?, email = ? WHERE id_user = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $novoNome, $novoEmail, $usuarioId);
    }

    if ($stmt->execute()) {
        $_SESSION['usuario'] = $novoNome;
        $mensagem = "Dados atualizados com sucesso!";
    } else {
        $mensagem = "Erro ao atualizar dados.";
    }

    $stmt->close();
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
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="container my-5">
    <div class="card mx-auto shadow" style="max-width: 600px;">
        <div class="card-body">
            <h3 class="card-title mb-4 text-center">Editar Perfil</h3>

            <?php if ($mensagem): ?>
                <div class="alert alert-info"><?= htmlspecialchars($mensagem) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($dadosUsuario['nome']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($dadosUsuario['email']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Nova Senha (opcional)</label>
                    <input type="password" class="form-control" id="senha" name="senha" placeholder="Deixe em branco para manter a senha atual">
                </div>
                <button type="submit" class="btn btn-success">Salvar Alterações</button>
                <a href="perfil_wegener.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
