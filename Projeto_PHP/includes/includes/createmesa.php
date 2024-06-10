<?php
session_start(); // Iniciar a sessão para mensagens de feedback
include 'dbphp.php'; // Conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['create_mesa'])) {
        $numero = $_POST['numero'];
        $capacidade = $_POST['capacidade'];

        // Verificar se todos os campos foram preenchidos
        if (empty($numero) || empty($capacidade)) {
            $_SESSION['message'] = "Todos os campos são obrigatórios para criar uma mesa.";
            $_SESSION['msg_type'] = "danger"; // Tipo de mensagem para Bootstrap
        } else {
            // Verificar se já existe uma mesa com o mesmo número
            $sql_check = "SELECT * FROM mesas WHERE numero = '$numero'";
            $result_check = $conn->query($sql_check);

            if ($result_check->num_rows > 0) {
                // Se existir uma mesa com o mesmo número
                $_SESSION['message'] = "Uma mesa com o mesmo número já existe.";
                $_SESSION['msg_type'] = "danger";
            } else {
                // Inserir nova mesa
                $sql = "INSERT INTO mesas (numero, capacidade) VALUES ('$numero', '$capacidade')";
                if ($conn->query($sql) === TRUE) {
                    $_SESSION['message'] = "Nova mesa criada com sucesso.";
                    $_SESSION['msg_type'] = "success";
                } else {
                    $_SESSION['message'] = "Erro: " . $sql . "<br>" . $conn->error;
                    $_SESSION['msg_type'] = "danger";
                }
            }
        }
        // Redirecionar para evitar reenvio do formulário ao atualizar a página
        header("Location: createmesa.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Criar Nova Mesa</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Criar Nova Mesa</h2>
    <?php
    // Mostrar mensagens de feedback
    if (isset($_SESSION['message'])):
    ?>
    <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show" role="alert">
        <?php
        echo $_SESSION['message'];
        unset($_SESSION['message']);
        unset($_SESSION['msg_type']);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <form method="post" action="createmesa.php">
        <div class="form-group">
            <label for="numero">Número da Mesa:</label>
            <input type="number" class="form-control" id="numero" name="numero" required>
        </div>
        <div class="form-group">
            <label for="capacidade">Capacidade:</label>
            <input type="number" class="form-control" id="capacidade" name="capacidade" required>
        </div>
        <button type="submit" class="btn btn-success" name="create_mesa">Adicionar Mesa</button>
        <a href="read.php" class="btn btn-danger" style="margin-left: 10px;">Voltar</a>
    </form>
</div>

<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
