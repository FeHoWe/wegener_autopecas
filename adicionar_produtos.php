<?php 
// Configurações do banco de dados
$host = 'localhost';
$dbname = 'wegener_autopecas';
$username = 'root';
$password = '';

try {
    // Estabelecendo conexão com o banco de dados
    $conexao = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar se os dados foram enviados via POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitizar e validar os campos recebidos
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
        $preco = filter_input(INPUT_POST, 'preco', FILTER_VALIDATE_FLOAT);
        $categoria = filter_input(INPUT_POST, 'categoria', FILTER_VALIDATE_INT); // id_cat

        // Verificar se os campos obrigatórios estão preenchidos corretamente
        if (!empty($nome) && !empty($descricao) && $preco !== false && $categoria !== false) {

            // Verificar se a categoria existe no banco de dados
            $queryCategoria = "SELECT COUNT(*) FROM categoria WHERE id_cat = :id_cat";
            $stmtCategoria = $conexao->prepare($queryCategoria);
            $stmtCategoria->bindParam(':id_cat', $categoria);
            $stmtCategoria->execute();
            $categoriaExiste = $stmtCategoria->fetchColumn();

            if ($categoriaExiste > 0) {
                // Verificar se o arquivo foi enviado
                if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                    $imagemTmp = $_FILES['imagem']['tmp_name'];
                    $imagemTipo = mime_content_type($imagemTmp);
                    $imagemNome = basename($_FILES['imagem']['name']);

                    // Validar se o arquivo é PNG
                    if ($imagemTipo === 'image/png') {
                        // Criar diretório se não existir
                        $caminhoDestinoDir = 'uploads/';
                        if (!is_dir($caminhoDestinoDir)) {
                            mkdir($caminhoDestinoDir, 0777, true);
                        }

                        $caminhoDestino = $caminhoDestinoDir . $imagemNome;

                        if (move_uploaded_file($imagemTmp, $caminhoDestino)) {
                            // Inserir o produto no banco de dados
                            $query = "INSERT INTO produto (nome, descricao, preco, categoria_id, imagem, ativo) 
                                      VALUES (:nome, :descricao, :preco, :categoria, :imagem, 'A')";
                            $stmt = $conexao->prepare($query);
                            $stmt->bindParam(':nome', $nome);
                            $stmt->bindParam(':descricao', $descricao);
                            $stmt->bindParam(':preco', $preco);
                            $stmt->bindParam(':categoria', $categoria);
                            $stmt->bindParam(':imagem', $caminhoDestino);
                            $stmt->execute();

                            echo "✅ Produto '$nome' adicionado com sucesso!";
                        } else {
                            echo "❌ Erro ao salvar o arquivo no servidor.";
                        }
                    } else {
                        echo "❌ Por favor, envie apenas arquivos no formato PNG.";
                    }
                } else {
                    echo "❌ Nenhum arquivo foi enviado.";
                }
            } else {
                echo "❌ Erro: A categoria selecionada não é válida.";
            }
        } else {
            echo "❌ Por favor, preencha todos os campos corretamente.";
        }
    }
} catch (PDOException $e) {
    echo "❌ Erro na conexão ou ao adicionar produto: " . htmlspecialchars($e->getMessage());
}
?>
