<?php

    include "header.php";
    include "conexaoBD.php";

    // Verifica se o ID da carta foi informado via GET
    if (isset($_GET['id_carta'])) {

        $idCarta = (int) $_GET['id_carta'];

        // Query para buscar os dados da carta
        $buscarCarta = "SELECT * FROM carta_pokemon WHERE id_carta = $idCarta";
        $resCarta    = mysqli_query($conn, $buscarCarta);

        // Verifica se a carta foi encontrada
        if (mysqli_num_rows($resCarta) > 0) {
            $carta        = mysqli_fetch_assoc($resCarta);
            $idCarta      = $carta['id_carta'];
            $nomeCarta    = $carta['nome'];
            $tipoCarta    = $carta['tipo'];
            $raridadeCarta= $carta['raridade'];
            $edicaoCarta  = $carta['edicao'];
            $precoCarta   = $carta['preco'];
            $estoqueCarta = $carta['estoque'];
            $imagemCarta  = $carta['imagem_url'];
        } else {
            echo "<div class='alert alert-danger text-center mt-5'>Carta não encontrada!</div>";
            include "footer.php";
            exit();
        }

    } else {
        echo "<div class='alert alert-danger text-center mt-5'>ID da carta não informado!</div>";
        include "footer.php";
        exit();
    }

?>

<section class="py-5">
    <div class="container px-4 px-lg-5 my-4">

        <!-- Botão Voltar -->
        <div class="mb-4">
            <a href="index.php" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Voltar ao Catálogo
            </a>
        </div>

        <div class="row gx-4 gx-lg-5 align-items-center">

            <!-- Imagem da Carta -->
            <div class="col-md-5 text-center">
                <img class="img-fluid rounded shadow mb-4 mb-md-0
                            <?php if ($estoqueCarta == 0) echo 'imagem-esgotada'; ?>"
                     src="<?php echo htmlspecialchars($imagemCarta); ?>"
                     alt="<?php echo htmlspecialchars($nomeCarta); ?>"
                     title="<?php echo htmlspecialchars($nomeCarta); ?>"
                     style="max-height: 420px; object-fit: contain;" />
            </div>

            <!-- Informações da Carta -->
            <div class="col-md-7">

                <!-- Tipo e Edição -->
                <p class="text-muted mb-1">
                    <i class="bi bi-tag me-1"></i>
                    <?php echo htmlspecialchars($tipoCarta); ?> &bull;
                    <?php echo htmlspecialchars($edicaoCarta); ?>
                </p>

                <!-- Nome da Carta -->
                <h1 class="display-5 fw-bolder mb-2">
                    <?php echo htmlspecialchars($nomeCarta); ?>
                </h1>

                <!-- Badge de Raridade -->
                <?php
                    $badgeClass = 'bg-secondary';
                    if (strpos($raridadeCarta, 'Hiper')    !== false) $badgeClass = 'bg-danger';
                    elseif (strpos($raridadeCarta, 'Ultra') !== false) $badgeClass = 'bg-purple' ;
                    elseif (strpos($raridadeCarta, 'Rara')  !== false) $badgeClass = 'bg-primary';
                ?>
                <span class="badge <?php echo $badgeClass; ?> fs-6 mb-3">
                    <i class="bi bi-gem me-1"></i>
                    <?php echo htmlspecialchars($raridadeCarta); ?>
                </span>

                <!-- Preço -->
                <div class="fs-3 fw-bolder text-success mb-3">
                    R$ <?php echo number_format($precoCarta, 2, ',', '.'); ?>
                </div>

                <!-- Estoque -->
                <p class="mb-4">
                    <?php if ($estoqueCarta > 0): ?>
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle me-1"></i>
                            <?php echo $estoqueCarta; ?> unidade(s) em estoque
                        </span>
                    <?php else: ?>
                        <span class="badge bg-danger">
                            <i class="bi bi-x-circle me-1"></i> Sem estoque
                        </span>
                    <?php endif; ?>
                </p>

                <!-- Tabela de Detalhes -->
                <table class="table table-bordered mb-4">
                    <tr>
                        <th class="bg-light" style="width:35%">Tipo</th>
                        <td><?php echo htmlspecialchars($tipoCarta); ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Raridade</th>
                        <td><?php echo htmlspecialchars($raridadeCarta); ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Edição</th>
                        <td><?php echo htmlspecialchars($edicaoCarta); ?></td>
                    </tr>
                </table>

                <!-- Botão de Ação -->
                <?php if ($estoqueCarta > 0): ?>

                    <?php if (isset($_SESSION['logado']) && $_SESSION['logado'] === true): ?>

                        <?php if ($tipoUsuario == 'administrador'): ?>
                            <!-- Admin: botão para editar -->
                            <a href="formEditarCarta.php?id_carta=<?php echo $idCarta; ?>"
                               class="btn btn-warning btn-lg fw-bold text-dark">
                                <i class="bi bi-pencil-square me-1"></i> Editar Carta
                            </a>
                        <?php else: ?>
                            <!-- Cliente: botão para comprar -->
                            <a href="efetuarPedido.php?id_carta=<?php echo $idCarta; ?>"
                               class="btn btn-warning btn-lg fw-bold text-dark">
                                <i class="bi bi-cart-fill me-1"></i> Comprar Agora
                            </a>
                        <?php endif; ?>

                    <?php else: ?>
                        <!-- Visitante: redireciona para login -->
                        <a href="formLogin.php" class="btn btn-outline-dark btn-lg">
                            <i class="bi bi-person me-1"></i> Faça login para comprar
                        </a>
                    <?php endif; ?>

                <?php else: ?>
                    <button class="btn btn-secondary btn-lg" disabled>
                        <i class="bi bi-x-circle me-1"></i> Indisponível
                    </button>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<!-- Seção de Cartas Relacionadas (mesmo tipo) -->
<section class="py-5 bg-light">
    <div class="container px-4 px-lg-5">
        <h2 class="fw-bolder mb-4">Cartas do mesmo tipo</h2>
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-4 justify-content-start">
            <?php
                $listarRelacionadas = "SELECT * FROM carta_pokemon
                                       WHERE tipo = '$tipoCarta'
                                       AND   id_carta != $idCarta
                                       AND   estoque > 0
                                       LIMIT 4";
                $resRelacionadas = mysqli_query($conn, $listarRelacionadas);

                if (mysqli_num_rows($resRelacionadas) > 0) {
                    while ($rel = mysqli_fetch_assoc($resRelacionadas)):
            ?>

            <div class="col mb-4">
                <a class="text-decoration-none text-dark"
                   href="visualizarCarta.php?id_carta=<?php echo $rel['id_carta']; ?>">
                    <div class="card h-100 card-hover">
                        <div class="card-overlay">
                            <i class="bi bi-eye me-1"></i> Ver
                        </div>
                        <img class="card-img-top"
                             src="<?php echo htmlspecialchars($rel['imagem_url']); ?>"
                             alt="<?php echo htmlspecialchars($rel['nome']); ?>"
                             style="height: 160px; object-fit: cover;" />
                        <div class="card-body p-3 text-center">
                            <h6 class="fw-bolder mb-1"><?php echo htmlspecialchars($rel['nome']); ?></h6>
                            <p class="text-success fw-bold mb-0">
                                R$ <?php echo number_format($rel['preco'], 2, ',', '.'); ?>
                            </p>
                        </div>
                    </div>
                </a>
            </div>

            <?php
                    endwhile;
                } else {
                    echo "<p class='text-muted'>Nenhuma carta relacionada encontrada.</p>";
                }
            ?>
        </div>
    </div>
</section>

<?php include "footer.php" ?>
