<?php
include 'dbphp.php';

// Verificar se o parâmetro 'id' foi passado através da URL
if (isset($_GET['id'])) {
    // Obter o valor do parâmetro 'id'
    $id = $_GET['id'];

    // Verificar se o formulário foi submetido
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recuperar os dados do formulário
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $ingredientes = $_POST['ingredientes'];
        $preco = $_POST['preco'];
        $categoria = $_POST['categoria'];

        // Preparar a consulta SQL para atualizar o prato com os novos dados
        $sql = "UPDATE pratos SET nome='$nome', descricao='$descricao', ingredientes='$ingredientes', preco=$preco, categoria='$categoria' WHERE id=$id";

        // Executar a consulta SQL de atualização
        if ($conn->query($sql) === TRUE) {
            echo "Prato atualizado com sucesso!";
        } else {
            echo "Erro ao atualizar prato: " . $conn->error;
        }
    }

    // Preparar a consulta SQL para selecionar o prato com o ID especificado
    $sql = "SELECT * FROM pratos WHERE id=$id";

    // Executar a consulta SQL
    $result = $conn->query($sql);

    // Verificar se a consulta retornou algum resultado
    if ($result->num_rows > 0) {
        // Extrair os dados do prato
        $row = $result->fetch_assoc();

        // Exibir o formulário de atualização com os dados do prato
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Editar Prato</title>
            <link rel="stylesheet" href="../css/bootstrap.min.css">
        </head>
        <body>
        <div class="container">
            <h2>Editar Prato</h2>
            <form method="post" action="">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $row['nome']; ?>">
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <textarea class="form-control" id="descricao" name="descricao"><?php echo $row['descricao']; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="ingredientes">Ingredientes:</label>
                    <textarea class="form-control" id="ingredientes" name="ingredientes"><?php echo $row['ingredientes']; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="preco">Preço:</label>
                    <input type="text" class="form-control" id="preco" name="preco" value="<?php echo $row['preco']; ?>">
                </div>
                <div class="form-group">
                    <label for="categoria">Categoria:</label>
                    <input type="text" class="form-control" id="categoria" name="categoria" value="<?php echo $row['categoria']; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Atualizar</button>
            </form>
        </div>
        <script src="../js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        <?php
    } else {
        echo "Nenhum prato encontrado com o ID especificado.";
    }
} else {
    echo "ID do prato não especificado.";
}
?>
