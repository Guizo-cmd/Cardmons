<?php include "header.php" ?>

<?php include "conexaoBD.php" ?>

<?php
    // Verifica se o usuário está logado e é administrador
    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || $_SESSION['tipoUsuario'] != 'administrador') {
        header("Location: formLogin.php");
        exit();
    }

    // Atualização de status via POST
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_pedido']) && isset($_POST['novoStatus'])) {

        $idPedido   = (int) $_POST['id_pedido'];
        $novoStatus = mysqli_real_escape_string($conn, $_POST['novoStatus']);

        $statusValidos = ['pendente', 'aprovado', 'enviado', 'entregue', 'cancelado'];

        if (in_array($novoStatus, $statusValidos)) {
            $atualizarStatus = "UPDATE pedido SET status = '$novoStatus' WHERE id_pedido = $idPedido";
            mysqli_query($conn, $atualizarStatus);
        }
    }

    $msgFeedback = $_GET['msg'] ?? '';
?>

<section class="py-5">
    <div class="container mt-3">

        <h3 class="fw-bolder mb-4">
            <i class="bi bi-bag-check me-2"></i> Gerenciar Pedidos
        </h3>

        <?php if ($msgFeedback == 'atualizado'): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-1"></i> Status do pedido atualizado com sucesso!
            </div>
        <?php endif; ?>

        <?php
            // Busca todos os pedidos com dados do usuário e da carta
            $listarPedidos = "
                SELECT
                    p.id_pedido,
                    p.data_pedido,
                    p.status,
                    p.valor_total,
                    u.nome       AS nome_usuario,
                    u.email      AS email_usuario,
                    i.quantidade,
                    c.nome       AS nome_carta
                FROM pedido p
                INNER JOIN usuario       u ON p.id_usuario  = u.id_usuario
                INNER JOIN item_pedido   i ON p.id_pedido   = i.id_pedido
                INNER JOIN carta_pokemon c ON i.id_carta    = c.id_carta
                ORDER BY p.data_pedido DESC
            ";

            $res          = mysqli_query($conn, $listarPedidos) or die("Erro ao listar pedidos!");
            $totalPedidos = mysqli_num_rows($res);

            $msg = ($totalPedidos == 1)
                ? "Há <strong>$totalPedidos</strong> pedido no sistema."
                : "Há <strong>$totalPedidos</strong> pedidos no sistema.";
            echo "<div class='alert alert-info text-center'>$msg</div>";
        ?>

        <!-- Tabela de Pedidos -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>PEDIDO</th>
                        <th>CLIENTE</th>
                        <th>CARTA</th>
                        <th>QTD.</th>
                        <th>TOTAL</th>
                        <th>STATUS</th>
                        <th>DATA</th>
                        <th class="text-center">AÇÃO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($pedido = mysqli_fetch_assoc($res)): ?>
                    <tr>
                        <td class="fw-bold">#<?php echo $pedido['id_pedido']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($pedido['nome_usuario']); ?></strong><br>
                            <small class="text-muted"><?php echo htmlspecialchars($pedido['email_usuario']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($pedido['nome_carta']); ?></td>
                        <td><?php echo $pedido['quantidade']; ?> un.</td>
                        <td class="fw-bold text-success">
                            R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?>
                        </td>
                        <td>
                            <?php
                                $status   = $pedido['status'];
                                $badgeMap = [
                                    'pendente'  => 'bg-warning text-dark',
                                    'aprovado'  => 'bg-success',
                                    'enviado'   => 'bg-info text-dark',
                                    'entregue'  => 'bg-primary',
                                    'cancelado' => 'bg-danger',
                                ];
                                $badgeClass = $badgeMap[$status] ?? 'bg-secondary';
                                echo "<span class='badge $badgeClass'>" . ucfirst($status) . "</span>";
                            ?>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></td>
                        <td class="text-center">
                            <!-- Formulário inline para atualizar o status -->
                            <form method="POST" action="gerenciarPedidos.php" class="d-flex gap-1">
                                <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                <select name="novoStatus" class="form-select form-select-sm" style="width:auto;">
                                    <?php
                                        $statusOpcoes = ['pendente','aprovado','enviado','entregue','cancelado'];
                                        foreach ($statusOpcoes as $op) {
                                            $sel = ($pedido['status'] == $op) ? 'selected' : '';
                                            echo "<option value='$op' $sel>" . ucfirst($op) . "</option>";
                                        }
                                    ?>
                                </select>
                                <button type="submit" class="btn btn-sm btn-warning fw-bold text-dark"
                                        title="Atualizar Status">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <?php mysqli_close($conn); ?>

    </div>
</section>

<?php include "footer.php" ?>
