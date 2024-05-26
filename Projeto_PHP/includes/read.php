<?php
include 'dbphp.php';

$sql = "SELECT * FROM pratos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pratos</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Lista de Pratos</h2>
    
    <a href="create.php" class="btn btn-success mb-3">Atualizar Menu</a>
    <a href="createpedido.php" class="btn btn-info mb-3"> Fazer pedido <a>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Ingredientes</th>
                <th>Preço</th>
                <th>Categoria</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['nome']}</td>
                        <td>{$row['descricao']}</td>
                        <td>{$row['ingredientes']}</td>
                        <td>{$row['preco']}</td>
                        <td>{$row['categoria']}</td>
                        <td>
                            <a href='update.php?id={$row['id']}' class='btn btn-warning'>Editar</a>
                            <a href='delete.php?id={$row['id']}' class='btn btn-danger'>Deletar</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Nenhum prato encontrado</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
