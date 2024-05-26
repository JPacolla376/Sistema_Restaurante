<?php
include 'dbphp.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $ingredientes = $_POST['ingredientes'];
    $preco = $_POST['preco'];
    $categoria = $_POST['categoria'];

    echo "Nome: " . $nome . "<br>";
    echo "Descrição: " . $descricao . "<br>";
    echo "Ingredientes: " . $ingredientes . "<br>";
    echo "Preço: " . $preco . "<br>";
    echo "Categoria: " . $categoria . "<br>";

    $sql = "INSERT INTO pratos (nome, descricao, ingredientes, preco, categoria)
            VALUES ('$nome', '$descricao', '$ingredientes', '$preco', '$categoria')";

    if ($conn->query($sql) === TRUE) {
        header("Location: read.php");
        exit();
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adicionar Prato</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Adicionar Novo Prato</h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome">
        </div>
        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea class="form-control" id="descricao" name="descricao"></textarea>
        </div>
        <div class="form-group">
            <label for="ingredientes">Ingredientes:</label>
            <textarea class="form-control" id="ingredientes" name="ingredientes"></textarea>
        </div>
        <div class="form-group">
            <label for="preco">Preço:</label>
            <input type="text" class="form-control" id="preco" name="preco">
        </div>
        <div class="form-group">
            <label for="categoria">Categoria:</label>
            <input type="text" class="form-control" id="categoria" name="categoria">
        </div>
        <button type="submit" class="btn btn-primary">Adicionar</button>
        <a href="read.php" class="btn btn-danger" style="margin-left: 10px;">Voltar</a>
    </form>
</div>
<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
