<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="lista_produtos.css">
</head>
<body>
<div class="container my-5">
    <h2 class="mb-4">Lista de Produtos</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">

    <?php
    $host = 'localhost';
    $dbname = 'wegener_autopecas';
    $user = 'root';
    $pass = '';

    try {
        $conexao = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sqlProdutos = "SELECT id_prod, nome, descricao, preco, imagem, ativo FROM produto";
        $stmtProdutos = $conexao->prepare($sqlProdutos);
        $stmtProdutos->execute();
        $produtos = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);

        if ($produtos) {
            foreach ($produtos as $row) {
                $id_prod = $row['id_prod'];
                $nome = htmlspecialchars($row['nome'], ENT_QUOTES, 'UTF-8');
                $descricao = htmlspecialchars($row['descricao'], ENT_QUOTES, 'UTF-8');
                $preco = number_format($row['preco'], 2, ',', '.');
                $srcImagem = htmlspecialchars($row['imagem'], ENT_QUOTES, 'UTF-8');
                $ativo = ($row["ativo"] === 'A') ? 'Ativo' : 'Inativo';
                $ativoClass = ($row["ativo"] === 'A') ? 'ativo' : 'inativo';

                $novoStatus = ($row["ativo"] === 'A') ? 'I' : 'A';
                $botaoTexto = ($row["ativo"] === 'A') ? 'Desativar' : 'Ativar';
                $botaoClasse = ($row["ativo"] === 'A') ? 'btn-danger' : 'btn-success';

                echo "
                <div class='col'>
                    <div class='card h-100'>
                        <img src='{$srcImagem}' class='card-img-top' alt='Imagem do produto'>
                        <div class='card-body'>
                            <h5 class='card-title'>{$nome}</h5>
                            <p class='card-text'>{$descricao}</p>
                            <p class='card-text'><strong>Preço:</strong> R$ {$preco}</p>
                            <p class='card-text status-text {$ativoClass}' id='status-{$id_prod}'><strong>Status:</strong> {$ativo}</p>
                        </div>
                        <div class='card-footer text-end'>
                            <button class='btn btn-sm {$botaoClasse}' onclick='atualizarStatusProduto({$id_prod}, \"{$novoStatus}\")'>
                                {$botaoTexto}
                            </button>
                        </div>
                    </div>
                </div>";
            }
        } else {
            echo "<div class='col'><p class='text-center'>Nenhum produto encontrado.</p></div>";
        }
    } catch (PDOException $e) {
        echo '<p class="text-danger text-center">Erro: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
    }
    ?>

    </div>

    <div class="text-center mt-4">
        <a href="home.php" class="btn btn-secondary">Voltar para Home</a>
    </div>
</div>

<script>
function atualizarStatusProduto(id, novoStatus) {
    $.post("alterar_status.php", { id_prod: id, ativo: novoStatus })
    .done(function(resposta) {
        if (resposta.trim() === "sucesso") {
            location.reload();
        } else {
            alert("Erro ao atualizar: " + resposta);
        }
    })
    .fail(function() {
        alert("Erro ao enviar a requisição.");
    });
}
</script>
</body>
</html>
