<?php include "header.php" ?>

    <?php

        // Verifica se o usuário está logado
        if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
            header("Location: index.php");
            exit();
        }

        // Se confirmou o logout via POST, destrói a sessão e redireciona
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmar'])) {
            session_unset();
            session_destroy();
            header("Location: index.php");
            exit();
        }

    ?>

    <div class="container py-5 mt-3">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <div class="card border-0 shadow-sm text-center">

                    <div class="card-header py-3" style="background-color: #1a1a2e;">
                        <h5 class="mb-0 fw-bold text-warning">
                            <i class="bi bi-box-arrow-right me-2"></i> Sair da conta
                        </h5>
                    </div>

                    <div class="card-body p-4">

                        <!-- Avatar / ícone do usuário -->
                        <div class="mb-3">
                            <i class="bi bi-person-circle" style="font-size: 4rem; color: #1a1a2e;"></i>
                        </div>

                        <!-- Nome do usuário logado -->
                        <p class="fw-bold fs-5 mb-1"><?php echo htmlspecialchars($nomeUsuario); ?></p>
                        <p class="text-muted mb-4" style="font-size: 0.9rem;">
                            <?php echo htmlspecialchars($emailUsuario); ?>
                        </p>

                        <p class="text-secondary mb-4">
                            Tem certeza que deseja sair da sua conta?
                        </p>

                        <!-- Botões de ação -->
                        <form method="POST" action="index.php">
                            <div class="d-flex gap-2 justify-content-center">
                                <button type="submit" name="confirmar" value="1"
                                        class="btn btn-danger fw-bold px-4">
                                    <i class="bi bi-box-arrow-right me-1"></i> Sim, sair
                                   <?php session_unset(); ?>
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary px-4">
                                    <i class="bi bi-x-circle me-1"></i> Cancelar
                                </a>
                            </div>
                        </form>

                    </div>

                </div>

            </div>
        </div>
    </div>

<?php include "footer.php" ?>
