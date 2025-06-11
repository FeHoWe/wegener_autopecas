<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login_wegener.css">
</head>
<body>
    <?php
    session_start();

    $mensagemErro = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $host = "localhost";
        $usuario = "root";
        $senha = "";
        $banco = "wegener_autopecas";

        $conn = new mysqli($host, $usuario, $senha, $banco);

        if ($conn->connect_error) {
            die("<div class='container mt-3'><div class='alert alert-danger'>Erro na conexão: " . $conn->connect_error . "</div></div>");
        }

        $email = $_POST["email"];
        $senhaDigitada = $_POST["password"];

        $sql = "SELECT id_user, nome, senha FROM usuarios_wegener WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($idUsuario, $nomeUsuario, $hashSenha);
            $stmt->fetch();

            if (password_verify($senhaDigitada, $hashSenha)) {
                // Sessões
                $_SESSION['usuario_id'] = $idUsuario;
                $_SESSION['usuario_nome'] = $nomeUsuario;
                $_SESSION['usuario'] = $nomeUsuario; // Necessário para home.php
                $_SESSION['email'] = $email;
                header("Location: home.php");
                exit;
            } else {
                $mensagemErro = "Email ou senha inválidos.";
            }
        } else {
            $mensagemErro = "Email ou senha inválidos.";
        }

        $stmt->close();
        $conn->close();
    }
    ?>

    <div class="container mt-5">
        <h2 class="mb-4">Login</h2>

        <?php if (!empty($mensagemErro)) : ?>
            <div class="alert alert-danger"><?= htmlspecialchars($mensagemErro) ?></div>
        <?php endif; ?>

        <form class="needs-validation" novalidate method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Digite o email" required>
                <div class="invalid-feedback">Por favor, insira um email válido.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Digite a senha" required>
                <div class="invalid-feedback">Por favor, insira uma senha válida.</div>
            </div>
            <button type="submit" class="btn btn-primary">Entrar</button>
            <a href="cadastro_wegener.php" class="btn btn-secondary">Cadastrar-se</a>
            <a href="home.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
    </script>
</body>
</html>
