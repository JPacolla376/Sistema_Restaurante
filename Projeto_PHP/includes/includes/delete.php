<?php
include 'dbphp.php';


if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM pratos WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Prato deletado com sucesso!";
    } else {
        echo "Erro ao deletar prato: " . $conn->error;
    }

    $conn->close();
    header("Location: read.php");
} else {
     echo "ID do prato não especificado.";
}
?>
