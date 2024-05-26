<?php
include 'dbphp.php';

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter os dados do formulário
    $mesa_id = $_POST['mesa_id'];
    $pratos = $_POST['pratos'];
    $quantidades = $_POST['quantidades'];
    $preco_total = $_POST['preco_total'];
    $status = 'pendente'; // Definir o status inicial como pendente
    // Podemos usar a função CURRENT_TIMESTAMP para obter a data e hora atual diretamente do banco de dados
    $sql = "INSERT INTO pedidos (mesa_id, pratos, preco_total, status) VALUES ('$mesa_id', '$pratos', '$preco_total', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo "Pedido criado com sucesso!";
    } else {
        echo "Erro ao criar o pedido: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Criar Pedido</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Criar Novo Pedido</h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="mesa_id">Mesa:</label>
            <input type="text" class="form-control" id="mesa_id" name="mesa_id">
        </div>
        <div class="form-group">
            <label for="pratos">Pratos:</label>
            <select multiple class="form-control" id="pratos" name="pratos[]">
                <?php
                // Fazer um select
                $sql = "SELECT * FROM pratos";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="quantidades">Quantidades:</label>
            <input type="text" class="form-control" id="quantidades" name="quantidades[]">
        </div>
        <div class="form-group">
            <label for="preco_total">Preço Total:</label>
            <input type="text" class="form-control" id="preco_total" name="preco_total">
        </div>
        <button type="submit" class="btn btn-primary">Criar Pedido</button>
    </form>
</div>
<div>
    <div class="container">
        <button type="submit" class="btn btn-secondary"> Voltar </button>
    </div>
                

<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
