<?php include "header.php" ?>

<?php include "conexaoBD.php" ?>

<?php

    // Recebe os filtros via método GET
    $filtroTipo     = $_GET['tipo']     ?? 'todos';
    $filtroRaridade = $_GET['raridade'] ?? 'todos';
    $filtroBusca    = $_GET['busca']    ?? '';

    // Monta a query conforme os filtros selecionados
    $where = [];

    if ($filtroTipo != 'todos') {
        $tipoEscapado = mysqli_real_escape_string($conn, $filtroTipo);
        $where[] = "tipo = '$tipoEscapado'";
    }

    if ($filtroRaridade != 'todos') {
        $raridadeEscapada = mysqli_real_escape_string($conn, $filtroRaridade);
        $where[] = "raridade = '$raridadeEscapada'";
    }

    if (!empty($filtroBusca)) {
        $buscaEscapada = mysqli_real_escape_string($conn, $filtroBusca);
        $where[] = "(nome LIKE '%$buscaEscapada%' OR edicao LIKE '%$buscaEscapada%')";
    }

    $clausulaWhere = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

    // Query principal com filtros aplicados
    $listarCartas = "SELECT * FROM carta_pokemon $clausulaWhere ORDER BY nome ASC";
    $res = mysqli_query($conn, $listarCartas);

    // Consultas para popular os filtros dinâmicos
    $resTipos     = mysqli_query($conn, "SELECT DISTINCT tipo FROM carta_pokemon ORDER BY tipo ASC");
    $resRaridades = mysqli_query($conn, "SELECT DISTINCT raridade FROM carta_pokemon ORDER BY raridade ASC");

?>

<!-- Seção do Catálogo -->
<section class="py-5" id="catalogo">
    <div class="container px-4 px-lg-5 mt-3">

        <div class="text-center mb-5">
            <h2 class="section-heading text-uppercase">Catálogo de Cartas</h2>
            <h3 class="section-subheading text-muted">Encontre a carta Pokémon que você procura</h3>
        </div>

        <!-- Formulário de Filtros -->
        <form method="GET" action="index.php" class="mb-5">
            <div class="row g-3 justify-content-center">

                <!-- Campo de Busca -->
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="busca"
                               placeholder="Buscar carta ou edição..."
                               value="<?php echo htmlspecialchars($filtroBusca); ?>">
                    </div>
                </div>

                <!-- Filtro por Tipo -->
                <div class="col-md-2">
                    <select name="tipo" class="form-select">
                        <option value="todos" <?php if ($filtroTipo == 'todos') echo "selected"; ?>>
                            Todos os Tipos
                        </option>
                        <?php while ($tipo = mysqli_fetch_assoc($resTipos)): ?>
                            <option value="<?php echo htmlspecialchars($tipo['tipo']); ?>"
                                <?php if ($filtroTipo == $tipo['tipo']) echo "selected"; ?>>
                                <?php echo htmlspecialchars($tipo['tipo']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Filtro por Raridade -->
                <div class="col-md-2">
                    <select name="raridade" class="form-select">
                        <option value="todos" <?php if ($filtroRaridade == 'todos') echo "selected"; ?>>
                            Todas as Raridades
                        </option>
                        <?php while ($raridade = mysqli_fetch_assoc($resRaridades)): ?>
                            <option value="<?php echo htmlspecialchars($raridade['raridade']); ?>"
                                <?php if ($filtroRaridade == $raridade['raridade']) echo "selected"; ?>>
                                <?php echo htmlspecialchars($raridade['raridade']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Botões -->
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-warning fw-bold flex-fill">
                        <i class="bi bi-funnel-fill me-1"></i> Filtrar
                    </button>
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>

            </div>
        </form>

        <!-- Total de Cartas Encontradas -->
        <?php
            $totalCartas = mysqli_num_rows($res);
            $msg = ($totalCartas == 1)
                ? "Há <strong>$totalCartas</strong> carta disponível no catálogo."
                : "Há <strong>$totalCartas</strong> cartas disponíveis no catálogo.";
            echo "<div class='alert alert-info text-center mb-4'>$msg</div>";
        ?>

        <!-- Grid de Cards de Cartas -->
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">

            <?php if ($totalCartas > 0): ?>
                <?php while ($carta = mysqli_fetch_assoc($res)): ?>

                <div class="col mb-5">
                    <a class="text-decoration-none text-dark"
                       href="visualizarCarta.php?id_carta=<?php echo $carta['id_carta']; ?>">

                        <div class="card h-100 card-hover">

                            <!-- Faixa de Sem Estoque -->
                            <?php if ($carta['estoque'] == 0): ?>
                                <div class="faixa-esgotado">ESGOTADO</div>
                            <?php endif; ?>

                            <!-- Overlay ao passar o mouse -->
                            <div class="card-overlay">
                                <i class="bi bi-eye me-2"></i> Ver Detalhes
                            </div>

                            <!-- Imagem da Carta -->
                            <img class="card-img-top <?php if ($carta['estoque'] == 0) echo 'imagem-esgotada'; ?>"
                                 src="<?php echo htmlspecialchars($carta['imagem_url']); ?>"
                                 alt="<?php echo htmlspecialchars($carta['nome']); ?>"
                                 style="height: 220px; object-fit: cover;" />

                            <!-- Conteúdo do Card -->
                            <div class="card-body p-4">
                                <div class="text-center">

                                    <!-- Nome da Carta -->
                                    <h5 class="fw-bolder mb-1">
                                        <?php echo htmlspecialchars($carta['nome']); ?>
                                    </h5>

                                    <!-- Tipo e Raridade -->
                                    <p class="text-muted small mb-2">
                                        <?php echo htmlspecialchars($carta['tipo']); ?> &bull;
                                        <?php echo htmlspecialchars($carta['edicao']); ?>
                                    </p>

                                    <!-- Badge de Raridade -->
                                    <?php
                                        $raridade = $carta['raridade'];
                                        $badgeClass = 'badge-comum';
                                        if (strpos($raridade, 'Hiper')   !== false) $badgeClass = 'badge-hiper';
                                        elseif (strpos($raridade, 'Ultra') !== false) $badgeClass = 'badge-ultra';
                                        elseif (strpos($raridade, 'Rara')  !== false) $badgeClass = 'badge-rara';
                                        elseif (strpos($raridade, 'Secreta') !== false) $badgeClass = 'badge-secreta';
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?> mb-2">
                                        <?php echo htmlspecialchars($raridade); ?>
                                    </span>

                                    <!-- Preço -->
                                    <p class="fw-bold text-success mb-0">
                                        R$ <?php echo number_format($carta['preco'], 2, ',', '.'); ?>
                                    </p>

                                    <!-- Estoque -->
                                    <small class="text-muted">
                                        <?php echo ($carta['estoque'] > 0) ? "Estoque: {$carta['estoque']} un." : "Sem estoque"; ?>
                                    </small>

                                </div>
                            </div>

                        </div>
                    </a>
                </div>

                <?php endwhile; ?>

            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        <i class="bi bi-emoji-frown me-2"></i>
                        Nenhuma carta encontrada com os filtros selecionados.
                        <a href="index.php" class="alert-link ms-2">Limpar filtros</a>
                    </div>
                </div>
            <?php endif; ?>

        </div>

    </div>
</section>

<?php include "footer.php" ?>
