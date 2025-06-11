<!-- Código completo corrigido -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login_wegener.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Cadastro de Usuário</h2>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "wegener_autopecas";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("<div class='alert alert-danger'>Falha na conexão: " . $conn->connect_error . "</div>");
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $tipo = "Cliente";
            $nome = $_POST["nome"];
            $email = $_POST["email"];
            $telefone = $_POST["telefone"];
            $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuarios_wegener (tipo, nome, email, telefone, senha) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $tipo, $nome, $email, $telefone, $senha);

            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Usuário cadastrado com sucesso!</div>";
            } else {
                echo "<div class='alert alert-danger'>Erro: " . $stmt->error . "</div>";
            }

            $stmt->close();
        }
        ?>
        <form method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome" required>
                <div class="invalid-feedback">Por favor, insira um nome válido.</div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Digite o email" required>
                <div class="invalid-feedback">Por favor, insira um email válido.</div>
            </div>
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="tel" class="form-control" id="telefone" name="telefone" pattern="\d{10,11}" placeholder="Digite o telefone (Apenas números)" required>
                <div class="invalid-feedback">Digite um telefone válido com 10 ou 11 dígitos.</div>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite a senha" required>
                <div class="invalid-feedback">Digite uma senha válida.</div>
            </div>
            <div class="mb-3">
                <label for="confirmaSenha" class="form-label">Confirmar Senha</label>
                <input type="password" class="form-control" id="confirmaSenha" name="confirmaSenha" placeholder="Confirme a senha" required>
                <div class="invalid-feedback">As senhas não coincidem.</div>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar Usuário</button>
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
                    const senha = document.getElementById('senha').value;
                    const confirmaSenha = document.getElementById('confirmaSenha').value;

                    if (senha !== confirmaSenha) {
                        event.preventDefault();
                        event.stopPropagation();
                        document.getElementById('confirmaSenha').setCustomValidity("Senhas diferentes");
                    } else {
                        document.getElementById('confirmaSenha').setCustomValidity("");
                    }

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
