<!DOCTYPE html>
<html lang="pt-BR"> <!-- Define o idioma principal como português do Brasil -->
<head>
    <meta charset="UTF-8"> <!-- Define o conjunto de caracteres como UTF-8 para suportar caracteres especiais -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Permite que o layout seja responsivo em dispositivos móveis -->
    <title>Gerenciamento de Produtos</title> <!-- Título da página exibido na aba do navegador -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Importa o CSS do Bootstrap para estilizar a página -->
    <style>
        /* Estilo para os status "ativo" e "inativo" */
        .ativo {
            color: green; /* Define a cor verde para status ativo */
        }
        .inativo {
            color: red; /* Define a cor vermelha para status inativo */
        }
        /* Estilo para os botões de ativar e desativar */
        .btn-ativar, .btn-desativar {
            cursor: pointer; /* Adiciona o cursor de "mão" nos botões */
        }
    </style>
</head>
<body>
    <div class="container mt-5"> <!-- Cria um contêiner com margem superior para centralizar o conteúdo -->
        <h2>Lista de Produtos</h2> <!-- Título principal da página -->
        <table class="table table-striped"> <!-- Define uma tabela com estilo listrado do Bootstrap -->
            <thead>
                <tr> <!-- Cabeçalho da tabela -->
                    <th>Nome</th> <!-- Coluna para nome do produto -->
                    <th>Descrição</th> <!-- Coluna para descrição do produto -->
                    <th>Preço</th> <!-- Coluna para preço do produto -->
                    <th>Quantidade</th> <!-- Coluna para quantidade do produto -->
                    <th>Status</th> <!-- Coluna para status (ativo/inativo) -->
                    <th>Ações</th> <!-- Coluna para ações (ativar/desativar) -->
                </tr>
            </thead>
            <tbody id="lista-produtos"> <!-- Corpo da tabela onde os produtos serão carregados dinamicamente -->
            </tbody>
        </table>
    </div>

    <script>
    // Função para atualizar o status de um produto
    async function atualizarStatusProduto(id_prod, novoStatus) {
        const formData = new FormData(); // Cria um objeto para armazenar os dados do produto
        formData.append('id_prod', id_prod); // Adiciona o ID do produto
        formData.append('ativo', novoStatus); // Adiciona o novo status do produto

        try {
            const response = await fetch('alterar_status.php', { // Faz uma requisição para o arquivo PHP
                method: 'POST', // Método HTTP usado na requisição
                body: formData // Dados enviados na requisição
            });

            if (!response.ok) { // Verifica se houve erro na requisição
                throw new Error(`Erro na requisição: ${response.status}`); // Lança uma exceção
            }

            const data = await response.text(); // Obtém a resposta como texto

            if (data === 'sucesso') { // Verifica se a atualização foi bem-sucedida
                const linhaProduto = document.getElementById(`produto-${id_prod}`); // Encontra a linha correspondente ao produto
                if (!linhaProduto) {
                    console.error(`Elemento produto-${id_prod} não encontrado.`); // Exibe erro se a linha não for encontrada
                    return;
                }

                const colunaStatus = linhaProduto.querySelector('.status-produto'); // Seleciona a coluna de status
                const colunaAcoes = linhaProduto.querySelector('.acoes-produto'); // Seleciona a coluna de ações

                if (novoStatus === 'A') { // Caso o novo status seja "ativo"
                    colunaStatus.textContent = 'Ativo'; // Atualiza o texto para "Ativo"
                    colunaStatus.className = 'status-produto ativo'; // Adiciona a classe CSS correspondente
                    colunaAcoes.innerHTML = `<button class="btn btn-sm btn-danger btn-desativar" onclick="atualizarStatusProduto(${id_prod}, 'I')">Desativar</button>`; // Atualiza o botão para "Desativar"
                } else { // Caso o novo status seja "inativo"
                    colunaStatus.textContent = 'Inativo'; // Atualiza o texto para "Inativo"
                    colunaStatus.className = 'status-produto inativo'; // Adiciona a classe CSS correspondente
                    colunaAcoes.innerHTML = `<button class="btn btn-sm btn-success btn-ativar" onclick="atualizarStatusProduto(${id_prod}, 'A')">Ativar</button>`; // Atualiza o botão para "Ativar"
                }
            } else {
                alert('Erro ao atualizar o status do produto.'); // Exibe mensagem de erro
            }
        } catch (error) {
            console.error('Ocorreu um erro:', error); // Exibe erro no console
            alert('Erro ao atualizar o status do produto.'); // Alerta o usuário sobre o erro
        }
    }

    // Função para carregar a lista de produtos
    async function carregarProdutos() {
        try {
            const response = await fetch('lista_produtos.php'); // Faz uma requisição para o arquivo PHP
            if (!response.ok) { // Verifica se houve erro na requisição
                throw new Error(`Erro na requisição: ${response.status}`); // Lança uma exceção
            }
            const data = await response.text(); // Obtém os dados como texto
            const listaProdutos = document.getElementById('lista-produtos'); // Seleciona o corpo da tabela
            if (listaProdutos) {
                listaProdutos.innerHTML = data; // Atualiza o HTML com os dados recebidos
            }
        } catch (error) {
            console.error('Erro ao carregar produtos:', error); // Exibe erro no console
            alert('Erro ao carregar os produtos.'); // Alerta o usuário sobre o erro
        }
    }

    // Carrega os produtos quando o DOM estiver completamente carregado
    document.addEventListener('DOMContentLoaded', carregarProdutos);
    </script>
</body>
</html>