<?php include "header.php" ?>

<?php include "conexaoBD.php" ?>

<?php
    // Verifica se o usuário está logado
    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
        header("Location: formLogin.php");
        exit();
    }

    $idUsuario = $_SESSION['idUsuario'];
?>

<section class="py-5">
    <div class="container mt-3">

        <h3 class="fw-bolder mb-4">
            <i class="bi bi-bag me-2"></i> Meus Pedidos
        </h3>

        <?php
            // Busca todos os pedidos do usuário logado com JOIN nos itens e cartas
            $listarPedidos = "
                SELECT
                    p.id_pedido,
                    p.data_pedido,
                    p.status,
                    p.valor_total,
                    i.quantidade,
                    i.preco_unit,
                    i.subtotal,
                    c.nome      AS nome_carta,
                    c.tipo      AS tipo_carta,
                    c.raridade  AS raridade_carta,
                    c.imagem_url
                FROM pedido p
                INNER JOIN item_pedido i ON p.id_pedido = i.id_pedido
                INNER JOIN carta_pokemon c ON i.id_carta = c.id_carta
                WHERE p.id_usuario = $idUsuario
                ORDER BY p.data_pedido DESC
            ";

            $res          = mysqli_query($conn, $listarPedidos) or die("Erro ao listar pedidos!");
            $totalPedidos = mysqli_num_rows($res);

            if ($totalPedidos > 0) {
                $msg = ($totalPedidos == 1)
                    ? "Você possui <strong>$totalPedidos</strong> pedido no sistema."
                    : "Você possui <strong>$totalPedidos</strong> pedidos no sistema.";
                echo "<div class='alert alert-info text-center'>$msg</div>";
            } else {
                echo "<div class='alert alert-warning text-center'>
                        <i class='bi bi-bag-x me-1'></i>
                        Você ainda não realizou nenhum pedido.
                        <a href='index.php' class='alert-link ms-2'>Ver catálogo</a>
                      </div>";
            }
        ?>

        <!-- Tabela de Pedidos -->
        <?php if ($totalPedidos > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>PEDIDO</th>
                        <th>CARTA</th>
                        <th>QTD.</th>
                        <th>PREÇO UNIT.</th>
                        <th>SUBTOTAL</th>
                        <th>STATUS</th>
                        <th>DATA</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($pedido = mysqli_fetch_assoc($res)): ?>
                    <tr>
                        <td class="fw-bold">#<?php echo $pedido['id_pedido']; ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?php echo htmlspecialchars($pedido['imagem_url']); ?>"
                                     alt="<?php echo htmlspecialchars($pedido['nome_carta']); ?>"
                                     style="width:50px; height:50px; object-fit:cover; border-radius:4px;"
                                     class="me-2">
                                <div>
                                    <strong><?php echo htmlspecialchars($pedido['nome_carta']); ?></strong><br>
                                    <small class="text-muted">
                                        <?php echo htmlspecialchars($pedido['tipo_carta']); ?> &bull;
                                        <?php echo htmlspecialchars($pedido['raridade_carta']); ?>
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td><?php echo $pedido['quantidade']; ?> un.</td>
                        <td>R$ <?php echo number_format($pedido['preco_unit'], 2, ',', '.'); ?></td>
                        <td class="fw-bold text-success">
                            R$ <?php echo number_format($pedido['subtotal'], 2, ',', '.'); ?>
                        </td>
                        <td>
                            <?php
                                $status    = $pedido['status'];
                                $badgeMap  = [
                                    'pendente'   => 'bg-warning text-dark',
                                    'aprovado'   => 'bg-success',
                                    'enviado'    => 'bg-info text-dark',
                                    'entregue'   => 'bg-primary',
                                    'cancelado'  => 'bg-danger',
                                ];
                                $badgeClass = $badgeMap[$status] ?? 'bg-secondary';
                                echo "<span class='badge $badgeClass'>" . ucfirst($status) . "</span>";
                            ?>
                        </td>
                        <td>
                            <?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <?php mysqli_close($conn); ?>

    </div>
</section>

<?php include "footer.php" ?>
