<?php
include 'dbphp.php';

// Verificar se o parâmetro 'id' foi passado através da URL
if (isset($_GET['id'])) {
    // Obter o valor do parâmetro 'id'
    $id = $_GET['id'];

    // Preparar a consulta SQL para deletar o prato com o ID especificado
    $sql = "DELETE FROM pratos WHERE id=$id";

    // Executar a consulta SQL
    if ($conn->query($sql) === TRUE) {
        echo "Prato deletado com sucesso!";
    } else {
        echo "Erro ao deletar prato: " . $conn->error;
    }

    // Fechar a conexão com o banco de dados
    $conn->close();

    // Redirecionar de volta para a página de leitura após a exclusão
    header("Location: read.php");
} else {
    // Se o parâmetro 'id' não foi passado, exibir uma mensagem de erro
    echo "ID do prato não especificado.";
}
?>
