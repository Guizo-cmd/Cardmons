<?php include "header.php" ?>

<?php
    // Verifica se o usuário está logado e é administrador
    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || $_SESSION['tipoUsuario'] != 'administrador') {
        header("Location: formLogin.php");
        exit();
    }
?>

    <!-- Seção para Adicionar Carta -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7">

                    <div class="card shadow-sm border-0 mt-3">
                        <div class="card-header text-center py-4"
                             style="background: linear-gradient(135deg, #1a1a2e, #0f3460);">
                            <h4 class="mb-0 text-warning">
                                <i class="bi bi-plus-circle me-2"></i> Adicionar Carta Pokémon
                            </h4>
                        </div>
                        <div class="card-body p-4">

                            <form action="actionCarta.php" method="POST" class="was-validated"
                                  enctype="multipart/form-data">

                                <!-- Nome da Carta -->
                                <div class="form-floating mt-3 mb-3">
                                    <input type="text" class="form-control" id="nome"
                                           placeholder="Nome da Carta" name="nome"
                                           maxlength="100" required>
                                    <label for="nome">Nome da Carta</label>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">Informe o nome da carta.</div>
                                </div>

                                <!-- Tipo -->
                                <div class="form-floating mt-3 mb-3">
                                    <select class="form-select" id="tipo" name="tipo" required>
                                        <option value="" disabled selected>Selecione o Tipo</option>
                                        <option value="Água">Água</option>
                                        <option value="Dragão">Dragão</option>
                                        <option value="Elétrico">Elétrico</option>
                                        <option value="Fada">Fada</option>
                                        <option value="Fogo">Fogo</option>
                                        <option value="Gelo">Gelo</option>
                                        <option value="Incolor">Incolor</option>
                                        <option value="Lutador">Lutador</option>
                                        <option value="Metal">Metal</option>
                                        <option value="Planta">Planta</option>
                                        <option value="Psíquico">Psíquico</option>
                                        <option value="Sombrio">Sombrio</option>
                                    </select>
                                    <label for="tipo">Tipo do Pokémon</label>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">Selecione o tipo.</div>
                                </div>

                                <!-- Raridade -->
                                <div class="form-floating mt-3 mb-3">
                                    <select class="form-select" id="raridade" name="raridade" required>
                                        <option value="" disabled selected>Selecione a Raridade</option>
                                        <option value="Comum">Comum</option>
                                        <option value="Incomum">Incomum</option>
                                        <option value="Rara">Rara</option>
                                        <option value="Ultra Rara">Ultra Rara</option>
                                        <option value="Hiper Rara">Hiper Rara</option>
                                        <option value="Secreta">Secreta</option>
                                    </select>
                                    <label for="raridade">Raridade</label>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">Selecione a raridade.</div>
                                </div>

                                <!-- Edição -->
                                <div class="form-floating mt-3 mb-3">
                                    <input type="text" class="form-control" id="edicao"
                                           placeholder="Edição" name="edicao"
                                           maxlength="50" required>
                                    <label for="edicao">Edição / Coleção</label>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">Informe a edição da carta.</div>
                                </div>

                                <!-- Preço -->
                                <div class="form-floating mt-3 mb-3">
                                    <input type="number" class="form-control" id="preco"
                                           placeholder="Preço" name="preco"
                                           step="0.01" min="0.01" required>
                                    <label for="preco">Preço (R$)</label>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">Informe um preço válido (mín. R$ 0,01).</div>
                                </div>

                                <!-- Estoque -->
                                <div class="form-floating mt-3 mb-3">
                                    <input type="number" class="form-control" id="estoque"
                                           placeholder="Estoque" name="estoque"
                                           min="0" required>
                                    <label for="estoque">Quantidade em Estoque</label>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">Informe a quantidade em estoque.</div>
                                </div>

                                <!-- Imagem -->
                                <div class="form-floating mt-3 mb-4">
                                    <input type="file" class="form-control" id="imagem"
                                           placeholder="Imagem" name="imagem" required>
                                    <label for="imagem">
                                        <i class="bi bi-image me-1"></i> Imagem da Carta (JPG/PNG/WEBP, máx. 5MB)
                                    </label>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">Selecione uma imagem.</div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-warning fw-bold text-dark btn-lg flex-fill">
                                        <i class="bi bi-plus-circle me-1"></i> Adicionar Carta
                                    </button>
                                    <a href="gerenciarCartas.php" class="btn btn-outline-secondary btn-lg">
                                        Cancelar
                                    </a>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

<?php include "footer.php" ?>
