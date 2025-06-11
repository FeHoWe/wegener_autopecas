<?php
session_start();

// Conexão com banco de dados
$dsn = 'mysql:host=localhost;dbname=wegener_autopecas;charset=utf8';
$usuario = 'root';
$senha = '';

$tipoUsuario = '';

try {
  $conexao = new PDO($dsn, $usuario, $senha);
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
} catch (PDOException $e) {
  die('<p class="text-danger text-center">Erro ao conectar: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>');
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wegener Autopeças</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="wegener_autopecas.css">
  <link rel="stylesheet" href="produtos_wegener.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  
  <style>
    .header_principal {
      background-color: #999;
      padding: 10px 0;
      border-bottom: 1px solid #ddd;
    }
  </style>

</head>
<body>
  <header class="header_principal">
    <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap">
      <div class="d-flex align-items-center">
        <img src="logo_wegener.png" alt="Logo Wegener" class="logo-personalizada">
      </div>
      <div class="d-flex gap-3 align-items-center">
        <div class="btn btn-success d-flex align-items-center gap-2">
          <i class="bi bi-whatsapp"></i>
          <span>(55) 99149-5642</span>
        </div>
        <div class="btn btn-primary d-flex align-items-center gap-2">
          <i class="bi bi-telephone-fill"></i>
          <span>(55) 3375-4385</span>
        </div>
        <div class="cart-container">
          <button class="btn btn-light d-flex align-items-center gap-2" onclick="toggleCart()">
            <i class="bi bi-cart-fill"></i>
            <span>Meu Carrinho</span>
          </button>
          <div class="cart-box d-none" id="cartBox">
            <span class="close" onclick="toggleCart()">×</span>
            <h3>MEU CARRINHO</h3>
            <p id="cartMessage">Você ainda não possui itens no carrinho.</p>
            <ul id="cartItems"></ul>
            <div class="subtotal">
              <p>SUBTOTAL<br><small></small></p>
              <strong id="subtotal">R$ 0,00</strong>
            </div>
            <div class="total">
              <p>TOTAL <br><small></small></p>
              <strong id="total">R$ 0,00</strong>
            </div>
            <button class="checkout btn btn-success mt-2">FECHAR COMPRA</button>
          </div>
        </div>
      </div>
      
      <div class="dropdown">
        <button class="btn btn-outline-dark dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
          <span class="d-none d-md-inline">
            <?php if (isset($_SESSION['usuario'])): ?>
              <?= htmlspecialchars($_SESSION['usuario']) ?>
            <?php else: ?>
              Conta
            <?php endif; ?>
          </span>
        </button>
        
        <ul class="dropdown-menu dropdown-menu-end">
          <?php if (isset($_SESSION['usuario'])): ?>
            <li><a class="dropdown-item" href="perfil_wegener.php">Perfil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout_wegener.php">Sair</a></li>
          <?php else: ?>
            <li><a class="dropdown-item" href="login_wegener.php">Login</a></li>
            <li><a class="dropdown-item" href="cadastro_wegener.php">Cadastro</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </header>

  <?php if (isset($_SESSION['usuario'])): ?>
    <div class="alert alert-success text-center d-flex align-items-center justify-content-center gap-2" id="loginMessage">
      <i class="bi bi-emoji-smile" style="font-size: 1.5rem;"></i>
      <strong>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario']) ?>! Você foi logado com sucesso.</strong>
    </div>
  <?php endif; ?>
  
  <nav class="mt-3 d-flex flex-wrap gap-2 justify-content-left">
  <div class="nav-wrapper w-100">
    <div class="menu-categorias">
      <button id="btn-menu"><span class="menu-icon">&#9776;</span> Todos</button>
      <div id="menu-lateral" class="menu-lateral">
        <h3>Categorias</h3>
        <ul>
          <?php
            try {
              $sql = "SELECT * FROM categoria";
              $stmt = $conexao->prepare($sql);
              $stmt->execute();
              $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

              foreach ($categorias as $row) {
                if (!empty($row['nome_categoria'])) {
                  echo "<li><a href='categoria.php?categoria_id=" . htmlspecialchars($row['id_cat']) . "'>" . htmlspecialchars($row['nome_categoria']) . "</a></li>";
                }
              }
            } catch (PDOException $e) {
              echo "<li>Erro: " . htmlspecialchars($e->getMessage()) . "</li>";
            }
          ?>
        </ul>
      </div>
    </div>
    <a href="home.php" class="btn btn-outline-dark">Home</a>
    <a href="sobre_nossahistoria.php" class="btn btn-outline-dark">Nossa História</a>
    <a href="produtos_wegener.php" class="btn btn-outline-dark">Produtos</a>

    <?php 
    if (isset($_SESSION['usuario']) && $tipoUsuario === 'Administrador'): ?>
      <div class="container mb-4">
        <div class="alert alert-warning text-center">
          <strong>Área Administrativa</strong>
        </div>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
          <a href="adicionar_categoria.php" class="btn btn-outline-dark"> Adicionar Categoria </a>
          <a href="produtos_input.html" class="btn btn-outline-dark"> Adicionar Produto </a>
          <a href="gerenciar_usuarios.php" class="btn btn-outline-dark"> Gerenciar Tipos de Usuário </a>
          <a href="lista_produtos.php" class="btn btn-outline-dark">Lista de Produtos</a>
        </div>
      </div>
    <?php endif; ?>
  </div>
</nav>

  <div class="container my-4">
  <div class="produtos-container" id="productList">
    <?php
      try {
        $sqlProdutos = "SELECT nome, descricao, preco, imagem FROM produto WHERE ativo = 'A'";
        $stmtProdutos = $conexao->prepare($sqlProdutos);
        $stmtProdutos->execute();
        $produtos = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);

        foreach ($produtos as $produto) {
          $preco = number_format($produto['preco'], 2, ',', '.');
          $precoFloat = floatval($produto['preco']);
          $nomeJS = htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8');
          $descricao = htmlspecialchars($produto['descricao'], ENT_QUOTES, 'UTF-8');
          $imagem = htmlspecialchars($produto['imagem'], ENT_QUOTES, 'UTF-8');
          $nome = htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8');

          echo "<div class='produto'>";
          echo "  <img src='$imagem' class='img-fluid mb-2' alt='$nome'>";
          echo "  <h3>$nome</h3>";
          echo "  <p class='descricao'>$descricao</p>";
          echo "  <p class='preco'>R$ {$preco}</p>";
          echo "  <div class='input-group mb-3'>";
          echo "    <span class='input-group-text'>Qtd</span>";
          echo "    <input type='number' class='form-control quantidade-produto' min='1' max='99' value='1'>";
          echo "  </div>";
          echo "  <button class='btn btn-outline-success w-100 mt-auto' onclick=\"addItem(this, '$nomeJS', $precoFloat)\">";
          echo "    <i class='bi bi-cart-plus'></i> Adicionar ao carrinho";
          echo "  </button>";
          echo "</div>";
        }
      } catch (PDOException $e) {
        echo '<p class="text-danger text-center">Erro: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
      }
    ?>
  </div>
</div>

  <footer class="mt-5 text-center bg-light py-3">
    <p>2025 Wegener Autopeças<sup>&reg;</sup> - Todos os direitos reservados.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="script_carrinho.js"></script>
  <script src="script_categoria.js"></script>
  <script src="script_usuario.js"></script>
</body>
</html>
