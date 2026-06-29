<?php include "header.php" ?>

    <!-- Seção de Login -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">

                    <div class="card shadow-sm border-0 mt-3">
                        <div class="card-header text-center py-4"
                             style="background: linear-gradient(135deg, #1a1a2e, #0f3460);">
                            <h4 class="mb-0 text-warning">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Acessar o Sistema
                            </h4>
                        </div>
                        <div class="card-body p-4">

                            <?php
                                // Verifica se há erro de login recebido por GET
                                if (isset($_GET['erroLogin'])) {
                                    $erroLogin = $_GET['erroLogin'];

                                    if ($erroLogin == 'dadosInvalidos') {
                                        echo "<div class='alert alert-warning text-center'>
                                                <i class='bi bi-exclamation-triangle me-1'></i>
                                                <strong>E-mail</strong> ou <strong>Senha</strong> inválidos!
                                              </div>";
                                    }
                                }
                            ?>

                            <form action="actionLogin.php" method="POST" class="was-validated">

                                <div class="form-floating mt-3 mb-3">
                                    <input type="email" class="form-control" id="email"
                                           placeholder="Email" name="email" required>
                                    <label for="email">
                                        <i class="bi bi-envelope me-1"></i> E-mail
                                    </label>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">Informe um e-mail válido.</div>
                                </div>

                                <div class="form-floating mt-3 mb-4">
                                    <input type="password" class="form-control" id="senha"
                                           placeholder="Senha" name="senha" required>
                                    <label for="senha">
                                        <i class="bi bi-lock me-1"></i> Senha
                                    </label>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">Informe sua senha.</div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-warning fw-bold text-dark btn-lg">
                                        <i class="bi bi-box-arrow-in-right me-1"></i> Entrar
                                    </button>
                                </div>

                            </form>

                            <hr>
                            <p class="text-center mb-0">
                                Ainda não tem conta?
                                <a href="formUsuario.php" class="fw-bold text-decoration-none">
                                    Cadastre-se aqui! <i class="bi bi-emoji-smile"></i>
                                </a>
                            </p>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

<?php include "footer.php" ?>
