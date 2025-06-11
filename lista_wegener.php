<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Lista de Usuários</h2>
    <a href="cadastro_wegener.php" class="btn btn-primary mb-3">Cadastrar Usuário</a>

    <?php
    $conn = new mysqli("localhost", "root", "", "wegener_autopecas");
    if ($conn->connect_error) {
        die("<div class='alert alert-danger'>Falha na conexão: " . $conn->connect_error . "</div>");
    }

    // Excluir usuário
    if (isset($_GET['excluir'])) {
        $id = intval($_GET['excluir']);
        $stmt = $conn->prepare("DELETE FROM usuarios_wegener WHERE id_user = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo "<script>alert('Usuário excluído com sucesso!'); window.location.href='lista_wegener.php';</script>";
        exit;
    }

    // Atualizar usuário
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_user'])) {
        $id = intval($_POST['id_user']);
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $tipo = $_POST['tipo'];

        $stmt = $conn->prepare("UPDATE usuarios_wegener SET nome=?, email=?, telefone=?, tipo=? WHERE id_user=?");
        $stmt->bind_param("ssssi", $nome, $email, $telefone, $tipo, $id);
        if ($stmt->execute()) {
            echo "<script>alert('Usuário atualizado com sucesso!'); window.location.href='lista_wegener.php';</script>";
            exit;
        } else {
            echo "<div class='alert alert-danger'>Erro ao atualizar: " . $conn->error . "</div>";
        }
    }

    // Listar usuários
    $result = $conn->query("SELECT * FROM usuarios_wegener");

    echo "<table class='table table-striped'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Tipo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = htmlspecialchars($row['id_user']);
            $nome = htmlspecialchars($row['nome']);
            $email = htmlspecialchars($row['email']);
            $telefone = htmlspecialchars($row['telefone']);
            $tipo = htmlspecialchars($row['tipo']);

            if (isset($_GET['editar']) && $_GET['editar'] == $id) {
                // Linha de edição (form fora da tr)
                echo "
                <tr>
                    <form method='POST'>
                        <td><input type='hidden' name='id_user' value='$id'>$id</td>
                        <td><input type='text' name='nome' value='$nome' class='form-control' required></td>
                        <td><input type='email' name='email' value='$email' class='form-control' required></td>
                        <td><input type='text' name='telefone' value='$telefone' class='form-control' required></td>
                        <td><input type='text' name='tipo' value='$tipo' class='form-control' required></td>
                        <td>
                            <button type='submit' class='btn btn-success btn-sm'>Salvar</button>
                            <a href='lista_usuarios.php' class='btn btn-secondary btn-sm'>Cancelar</a>
                        </td>
                    </form>
                </tr>";
            } else {
                // Linha normal
                echo "
                <tr>
                    <td>$id</td>
                    <td>$nome</td>
                    <td>$email</td>
                    <td>$telefone</td>
                    <td>$tipo</td>
                    <td>
                        <a href='?editar=$id' class='btn btn-warning btn-sm'>Editar</a>
                        <a href='?excluir=$id' class='btn btn-danger btn-sm' onclick='return confirm(\"Tem certeza que deseja excluir este usuário?\")'>Excluir</a>
                    </td>
                </tr>";
            }
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>Nenhum usuário encontrado.</td></tr>";
    }

    echo "</tbody></table>";
    $conn->close();
    ?>
</div>
</body>
</html>
