<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome_produto'];
    $preco = $_POST['preco'];

    $item = [
        'nome' => $nome,
        'preco' => $preco,
        'quantidade' => 1
    ];

    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }

    $_SESSION['carrinho'][] = $item;

    header('Location: carrinho.php'); // ou uma página de carrinho
    exit;
}
?>
