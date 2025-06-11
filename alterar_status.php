<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wegener_autopecas";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8mb4");

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_prod'], $_POST['ativo'])) {
        $id_prod = filter_input(INPUT_POST, 'id_prod', FILTER_VALIDATE_INT);
        $ativo = strtoupper(trim($_POST['ativo']));

        if ($id_prod && in_array($ativo, ['A', 'I'])) {
            $stmt = $conn->prepare("UPDATE produto SET ativo = ? WHERE id_prod = ?");
            $stmt->bind_param("si", $ativo, $id_prod);
            $stmt->execute();

            echo "sucesso";
        } else {
            http_response_code(400);
            echo "Dados inválidos.";
        }
    } else {
        http_response_code(405);
        echo "Requisição inválida.";
    }

    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo "Erro: " . $e->getMessage();
}
?>
