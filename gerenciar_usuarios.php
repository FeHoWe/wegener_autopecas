<?php
session_start();

if (!isset($_SESSION['usuario'])) {
  header("Location: login_wegener.php");
  exit;
}

$dsn = 'mysql:host=localhost;dbname=wegener_autopecas;charset=utf8';
$usuarioBD = 'root';
$senhaBD = '';

try {
  $conexao = new PDO($dsn, $usuarioBD, $senhaBD);
  $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Verificar tipo de usuário se estiver logado
  if (!empty($_SESSION['usuario']) && !empty($_SESSION['email'])) {
    $emailUsuario = $_SESSION['email'];

    $sqlTipo = "SELECT tipo FROM usuarios_wegener WHERE email = :email LIMIT 1";
    $stmtTipo = $conexao->prepare($sqlTipo);
    $stmtTipo->bindParam(':email', $emailUsuario, PDO::PARAM_STR);
    $stmtTipo->execute();
    $resultadoTipo = $stmtTipo->fetch(PDO::FETCH_ASSOC);

    if ($resultadoTipo && isset($resultadoTipo['tipo'])) {
      $tipoUsuario = $resultadoTipo['tipo'];
    }
  }

  if (!isset($tipoUsuario) || $tipoUsuario !== 'Administrador') {
    echo "<p class='text-danger text-center mt-5'>Acesso negado. Você não tem permissão para visualizar esta página.</p>";
    exit;
  }

  // Atualizar tipo de usuário se o formulário for enviado
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_user']) && isset($_POST['novo_tipo'])) {
    $id_user = $_POST['id_user'];
    $novo_tipo = $_POST['novo_tipo'];

    $stmtUpdate = $conexao->prepare("UPDATE usuarios_wegener SET tipo = :tipo WHERE id_user = :id_user");
    $stmtUpdate->bindParam(':tipo', $novo_tipo);
    $stmtUpdate->bindParam(':id_user', $id_user);
    $stmtUpdate->execute();

    echo "<script>alert('Tipo de usuário atualizado com sucesso!'); window.location.href='gerenciar_usuarios.php';</script>";
    exit;
  }

  // Buscar todos os usuários
  $stmtUsuarios = $conexao->query("SELECT id_user, nome, email, telefone, tipo FROM usuarios_wegener ORDER BY nome");
  $usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
  die('<p class="text-danger text-center">Erro ao conectar: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>');
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Gerenciar Usuários</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="gerenciar_usuarios.css">
</head>
<body class="container mt-4">
  <h2 class="mb-4">Gerenciar Usuários</h2>

  <table class="table table-striped table-bordered">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Telefone</th>
        <th>Tipo</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($usuarios as $user): ?>
        <tr>
          <td><?= htmlspecialchars($user['id_user']) ?></td>
          <td><?= htmlspecialchars($user['nome']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td><?= htmlspecialchars($user['telefone']) ?></td>
          <td><?= htmlspecialchars($user['tipo']) ?></td>
          <td>
            <form method="POST" class="d-flex gap-2">
              <input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">
              <select name="novo_tipo" class="form-select" style="width: auto;">
                <option value="Cliente" <?= $user['tipo'] === 'Cliente' ? 'selected' : '' ?>>Cliente</option>
                <option value="Administrador" <?= $user['tipo'] === 'Administrador' ? 'selected' : '' ?>>Administrador</option>
              </select>
              <button type="submit" class="btn btn-sm btn-primary">Atualizar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <a href="home.php" class="btn btn-secondary mt-3">Voltar para Home</a>
</body>
</html>
