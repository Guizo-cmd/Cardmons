<?php include "header.php" ?>

    <!-- Seção de Cadastro de Usuário -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">

                    <div class="card shadow-sm border-0 mt-3">
                        <div class="card-header text-center py-4"
                             style="background: linear-gradient(135deg, #1a1a2e, #0f3460);">
                            <h4 class="mb-0 text-warning">
                                <i class="bi bi-person-plus me-2"></i> Cadastro de Usuário
                            </h4>
                        </div>
                        <div class="card-body p-4">

                            <form action="actionUsuario.php" method="POST" class="was-validated">

                                <!-- Nome Completo -->
                                <div class="form-floating mt-3 mb-3">
                                    <input type="text" class="form-control" id="nome"
                                           placeholder="Nome Completo" name="nome"
                                           minlength="3" maxlength="100" required>
                                    <label for="nome">
                                        <i class="bi bi-person me-1"></i> Nome Completo
                                    </label>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">Informe seu nome completo (mín. 3 caracteres).</div>
                                </div>

                                <!-- E-mail -->
                                <div class="form-floating mt-3 mb-3">
                                    <input type="email" class="form-control" id="email"
                                           placeholder="E-mail" name="email"
                                           maxlength="100" required>
                                    <label for="email">
                                        <i class="bi bi-envelope me-1"></i> E-mail
                                    </label>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">Informe um e-mail válido.</div>
                                </div>

                                <!-- Senha -->
                                <div class="form-floating mt-3 mb-3">
                                    <input type="password" class="form-control" id="senha"
                                           placeholder="Senha" name="senha"
                                           minlength="4" required>
                                    <label for="senha">
                                        <i class="bi bi-lock me-1"></i> Senha
                                    </label>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">A senha deve ter pelo menos 4 caracteres.</div>
                                </div>

                                <!-- Confirmar Senha -->
                                <div class="form-floating mt-3 mb-4">
                                    <input type="password" class="form-control" id="confirmarSenha"
                                           placeholder="Confirmar Senha" name="confirmarSenha"
                                           minlength="4" required>
                                    <label for="confirmarSenha">
                                        <i class="bi bi-lock-fill me-1"></i> Confirmar Senha
                                    </label>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">Confirme sua senha.</div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-warning fw-bold text-dark btn-lg">
                                        <i class="bi bi-person-check me-1"></i> Cadastrar
                                    </button>
                                </div>

                            </form>

                            <hr>
                            <p class="text-center mb-0">
                                Já tem conta?
                                <a href="formLogin.php" class="fw-bold text-decoration-none">
                                    Faça login aqui!
                                </a>
                            </p>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

<?php include "footer.php" ?>
