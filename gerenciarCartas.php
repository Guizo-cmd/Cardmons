<?php include "header.php" ?>

<?php include "conexaoBD.php" ?>

<?php
    // Verifica se o usuário está logado e é administrador
    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || $_SESSION['tipoUsuario'] != 'administrador') {
        header("Location: formLogin.php");
        exit();
    }

    // Verifica se há mensagem de sucesso/erro vinda de outra página
    $mensagem = $_GET['msg'] ?? '';
?>

<section class="py-5">
    <div class="container mt-3">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bolder mb-0">
                <i class="bi bi-collection me-2"></i> Gerenciar Cartas
            </h3>
            <a href="formCarta.php" class="btn btn-warning fw-bold text-dark">
                <i class="bi bi-plus-circle me-1"></i> Adicionar Carta
            </a>
        </div>

        <?php
            // Exibe mensagens de feedback
            if ($mensagem == 'removida') {
                echo "<div class='alert alert-success'>
                        <i class='bi bi-check-circle me-1'></i>
                        Carta <strong>removida</strong> com sucesso!
                      </div>";
            }

            // Query para listar TODAS as cartas
            $listarCartas = "SELECT * FROM carta_pokemon ORDER BY nome ASC";
            $res          = mysqli_query($conn, $listarCartas) or die("Erro ao listar as cartas!");
            $totalCartas  = mysqli_num_rows($res);

            if ($totalCartas > 0) {
                echo "<div class='alert alert-info text-center'>
                        Há <strong>$totalCartas</strong> carta(s) cadastrada(s) no sistema.
                      </div>";
            } else {
                echo "<div class='alert alert-warning text-center'>
                        Nenhuma carta cadastrada no sistema.
                      </div>";
            }
        ?>

        <!-- Tabela de Cartas -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>IMAGEM</th>
                        <th>NOME</th>
                        <th>TIPO</th>
                        <th>RARIDADE</th>
                        <th>EDIÇÃO</th>
                        <th>PREÇO</th>
                        <th>ESTOQUE</th>
                        <th class="text-center">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($carta = mysqli_fetch_assoc($res)): ?>
                    <tr>
                        <td><?php echo $carta['id_carta']; ?></td>
                        <td>
                            <img src="<?php echo htmlspecialchars($carta['imagem_url']); ?>"
                                 alt="<?php echo htmlspecialchars($carta['nome']); ?>"
                                 style="width:60px; height:60px; object-fit:cover; border-radius:4px;">
                        </td>
                        <td class="fw-bold"><?php echo htmlspecialchars($carta['nome']); ?></td>
                        <td><?php echo htmlspecialchars($carta['tipo']); ?></td>
                        <td>
                            <span class="badge bg-primary">
                                <?php echo htmlspecialchars($carta['raridade']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($carta['edicao']); ?></td>
                        <td class="text-success fw-bold">
                            R$ <?php echo number_format($carta['preco'], 2, ',', '.'); ?>
                        </td>
                        <td>
                            <?php if ($carta['estoque'] > 0): ?>
                                <span class="badge bg-success"><?php echo $carta['estoque']; ?> un.</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Esgotado</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <!-- Botão Visualizar -->
                            <a href="visualizarCarta.php?id_carta=<?php echo $carta['id_carta']; ?>"
                               class="btn btn-sm btn-outline-secondary me-1"
                               title="Visualizar">
                                <i class="bi bi-eye"></i>
                            </a>
                            <!-- Botão Editar -->
                            <a href="formEditarCarta.php?id_carta=<?php echo $carta['id_carta']; ?>"
                               class="btn btn-sm btn-warning me-1"
                               title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <!-- Botão Remover -->
                            <a href="actionRemoverCarta.php?id_carta=<?php echo $carta['id_carta']; ?>"
                               class="btn btn-sm btn-danger"
                               title="Remover"
                               onclick="return confirm('Tem certeza que deseja remover a carta \'<?php echo htmlspecialchars($carta['nome']); ?>\'?')">
                                <i class="bi bi-trash"></i>
                            </a>
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
