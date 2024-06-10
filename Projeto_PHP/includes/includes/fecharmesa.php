<?php
session_start();
include 'dbphp.php';

class FecharMesaController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function fecharMesa($mesa_id) {
        // Calcular o valor total dos pedidos para essa mesa
        $sql_total = "SELECT SUM(preco_total) as total FROM pedidos WHERE mesa_id = ?";
        $stmt_total = $this->conn->prepare($sql_total);
        $stmt_total->bind_param("i", $mesa_id);
        $stmt_total->execute();
        $result_total = $stmt_total->get_result();
        $row_total = $result_total->fetch_assoc();
        $valor_total = $row_total['total'];

        // Atualizar o status da mesa para 'fechada'
        $sql_update = "UPDATE mesas SET status = 'fechada' WHERE id = ?";
        $stmt_update = $this->conn->prepare($sql_update);
        $stmt_update->bind_param("i", $mesa_id);
        
        if ($stmt_update->execute()) {
            $_SESSION['message'] = "Mesa fechada com sucesso. Valor total: R$ " . number_format($valor_total, 2);
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "Erro ao fechar a mesa: " . $stmt_update->error;
            $_SESSION['msg_type'] = "danger";
        }
    }

    public function getPedidosPorMesa($mesa_id) {
        // Consultar todos os pedidos para essa mesa
        // Verificar motivo de não estar buscando os disponíveis.
        $sql = "SELECT * FROM pedidos WHERE mesa_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $mesa_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $pedidos = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pedidos[] = $row;
            }
        }
        return $pedidos;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['fechar_mesa'])) {
        $fecharMesaController = new FecharMesaController($conn);
        $fecharMesaController->fecharMesa($_POST['mesa_id']);
    }
    
    // Redirecionar para evitar reenvio do formulário ao atualizar a página
    // Fase teste, havera mudanças
    header("Location: fecharmesa.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fechar Mesa</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Fechar Mesa</h2>
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

    <form method="post" action="fecharmesa.php">
        <div class="form-group">
            <label for="mesa_id">Mesa:</label>
            <select class="form-control" id="mesa_id" name="mesa_id" required>
                <?php
                // Listar todas as mesas
                $sql = "SELECT * FROM mesas";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>Mesa " . $row['numero'] . " - Status: " . $row['status'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nenhuma mesa disponível</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-danger" name="fechar_mesa">Fechar Mesa</button>
        <a href="read.php" class="btn btn-secondary" style="margin-left: 10px;">Voltar</a>
    </form>

    <!-- Relatório de Pedidos para verificação de fechamento-->
    <div class="mt-4">
        <h3>Relatório de Pedidos</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID do Pedido</th>
                    <th>Pratos</th>
                    <th>Preço Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Se uma mesa foi selecionada
                if (isset($_POST['mesa_id'])) {
                    $mesa_id = $_POST['mesa_id'];
                    // Recuperar os pedidos da mesa selecionada
                    // Corrigir erro de não estar buscando os pedidos
                    $pedidos = $fecharMesaController->getPedidosPorMesa($mesa_id);
                    $total_pedidos = count($pedidos);
                    if ($total_pedidos > 0) {
                        foreach ($pedidos as $pedido) {
                            echo "<tr>";
                            echo "<td>" . $pedido['id'] . "</td>";
                            echo "<td>" . $pedido['pratos'] . "</td>";
                            echo "<td>R$ " . number_format($pedido['preco_total'], 2) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Nenhum pedido encontrado para esta mesa.</td></tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
