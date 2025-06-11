<?php
// Configuração do banco de dados
$host = 'localhost';
$dbname = 'wegener_autopecas';
$user = 'root';
$pass = '';

// Conectar ao banco de dados
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeCategoria = $_POST['nome_categoria'] ?? '';

    if (!empty($nomeCategoria)) {
        // Função para adicionar uma categoria
        function adicionarCategoria($nomeCategoria) {
            global $pdo;
            try {
                $sql = "INSERT INTO categoria (nome_categoria) VALUES (:nome_categoria)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':nome_categoria', $nomeCategoria);
                $stmt->execute();
                echo "Categoria '$nomeCategoria' adicionada com sucesso!";
            } catch (PDOException $e) {
                echo "Erro ao adicionar categoria: " . $e->getMessage();
            }
        }

        // Chamar a função para adicionar a categoria
        adicionarCategoria($nomeCategoria);
    } else {
        echo "Por favor, preencha o campo de nome da categoria.";
    }
}
?>