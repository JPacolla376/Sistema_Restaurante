<?php
session_start();

class PedidoController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function criarPedido($mesa_id, $pratos_ids, $quantidades) {
        $preco_total = 0;
        $status = 'pendente';
        $pratos_nomes = array();

        foreach ($pratos_ids as $key => $prato_id) {
            $sql_prato = "SELECT nome, preco FROM pratos WHERE id = ?";
            $stmt = $this->conn->prepare($sql_prato);
            $stmt->bind_param("i", $prato_id);
            $stmt->execute();
            $result_prato = $stmt->get_result();

            if ($result_prato->num_rows > 0) {
                $row = $result_prato->fetch_assoc();
                $prato_nome = $row['nome'];
                $prato_preco = $row['preco'];

                $pratos_nomes[] = $prato_nome;
                $preco_total += $prato_preco * $quantidades[$key];
            }
            $stmt->close();
        }

        $pratos_string = implode(",", $pratos_nomes);
        $sql = "INSERT INTO pedidos (mesa_id, pratos, preco_total, status) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isds", $mesa_id, $pratos_string, $preco_total, $status);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Novo pedido criado com sucesso!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "Erro ao criar o pedido: " . $stmt->error;
            $_SESSION['msg_type'] = "danger";
            error_log("Erro ao criar o pedido: " . $stmt->error);
        }
        $stmt->close();
    }
}

include 'dbphp.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['create_pedido'])) {
        $pedidoController = new PedidoController($conn);
        $pedidoController->criarPedido($_POST['mesa_id'], $_POST['pratos'], $_POST['quantidades']);
        header("Location: criarpedido.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Criar Novo Pedido</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script>
        function calcularPrecoTotal() {
            let total = 0;
            let pratos = document.querySelectorAll("select#pratos option:checked");
            let quantidades = document.querySelectorAll("input[name='quantidades[]']");

            pratos.forEach((prato, index) => {
                let preco = parseFloat(prato.getAttribute('data-preco'));
                let quantidade = parseInt(quantidades[index].value) || 0;
                total += preco * quantidade;
            });

            document.getElementById('preco_total').value = total.toFixed(2);
        }
    </script>
</head>
<body>
<div class="container mt-4">
    <h2>Criar Novo Pedido</h2>
    <?php
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

    <form method="post" action="">
        <div class="form-group">
            <label for="mesa_id">Mesa:</label>
            <select class="form-control" id="mesa_id" name="mesa_id" required>
                <?php
                // Consultar mesas disponíveis
                $sql = "SELECT * FROM mesas WHERE status = 'disponível'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>Mesa " . $row['numero'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nenhuma mesa disponível</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="pratos">Pratos:</label>
            <select multiple class="form-control" id="pratos" name="pratos[]" onchange="calcularPrecoTotal()" required>
                <?php
                // Consultar pratos disponíveis
                $sql = "SELECT * FROM pratos";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "' data-preco='" . $row['preco'] . "'>" . $row['nome'] . "</option>";
                    }
                }
                ?>
            </select>
            <div class="form-group">
    <label for="quantidades">Quantidades:</label>
    <?php
    // Adicionar campos de quantidade com base nos pratos selecionados
    $result = $conn->query("SELECT * FROM pratos");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<label for='quantidade_" . $row['id'] . "'>Quantidade de " . $row['nome'] . ":</label>";
            echo "<input type='number' class='form-control' id='quantidade_" . $row['id'] . "' name='quantidades[]' value='1' min='1'>";
        }
    }
    ?>
</div>

        <div class="form-group">
            <label for="preco_total">Preço Total:</label>
            <input type="text" class="form-control" id="preco_total" name="preco_total" readonly required>
        </div>
        <button type="submit" class="btn btn-primary" name="create_pedido">Adicionar Pedido</button>
        <a href="read.php" class="btn btn-danger" style="margin-left: 10px;">Voltar</a>
    </form>
</div>

<script src="../js/bootstrap.bundle.min.js"></script>
<script>
    // Função para calcular o preço total
    function calcularPrecoTotal() {
        let total = 0;
        let pratos = document.querySelectorAll("select#pratos option:checked");
        let quantidades = document.querySelectorAll("input[name='quantidades[]']");

        pratos.forEach((prato, index) => {
            let preco = parseFloat(prato.getAttribute('data-preco'));
            let quantidade = parseInt(quantidades[index].value) || 0;
            total += preco * quantidade;
        });

        document.getElementById('preco_total').value = total.toFixed(2);
    }
</script>
</body>
</html>

