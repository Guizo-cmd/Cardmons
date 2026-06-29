<?php

    include "header.php";
    include "conexaoBD.php";

    // Verifica se o usuário está logado e é administrador
    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || $_SESSION['tipoUsuario'] != 'administrador') {
        header("Location: formLogin.php");
        exit();
    }

    // Verifica se o ID da carta foi informado via GET
    if (isset($_GET['id_carta'])) {

        $idCarta = (int) $_GET['id_carta'];

        // Busca os dados atuais da carta
        $buscarCarta = "SELECT * FROM carta_pokemon WHERE id_carta = $idCarta";
        $res         = mysqli_query($conn, $buscarCarta);

        if (mysqli_num_rows($res) > 0) {
            $carta = mysqli_fetch_assoc($res);
        } else {
            echo "<div class='alert alert-danger text-center mt-5'>Carta não encontrada!</div>";
            include "footer.php";
            exit();
        }

    } else {
        header("Location: gerenciarCartas.php");
        exit();
    }

?>

    <!-- Seção para Editar Carta -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7">

                    <div class="card shadow-sm border-0 mt-3">
                        <div class="card-header text-center py-4"
                             style="background: linear-gradient(135deg, #1a1a2e, #0f3460);">
                            <h4 class="mb-0 text-warning">
                                <i class="bi bi-pencil-square me-2"></i> Editar Carta Pokémon
                            </h4>
                        </div>
                        <div class="card-body p-4">

                            <!-- Imagem atual da carta -->
                            <div class="text-center mb-4">
                                <img src="<?php echo htmlspecialchars($carta['imagem_url']); ?>"
                                     alt="<?php echo htmlspecialchars($carta['nome']); ?>"
                                     style="max-height:150px; object-fit:contain;"
                                     class="img-thumbnail">
                                <p class="text-muted small mt-1">Imagem atual</p>
                            </div>

                            <form action="actionEditarCarta.php" method="POST" class="was-validated"
                                  enctype="multipart/form-data">

                                <!-- Campo oculto: ID da carta -->
                                <input type="hidden" name="id_carta"   value="<?php echo $carta['id_carta'];   ?>">
                                <input type="hidden" name="imagemAtual" value="<?php echo htmlspecialchars($carta['imagem_url']); ?>">

                                <!-- Nome da Carta -->
                                <div class="form-floating mt-3 mb-3">
                                    <input type="text" class="form-control" id="nome"
                                           placeholder="Nome da Carta" name="nome"
                                           maxlength="100" required
                                           value="<?php echo htmlspecialchars($carta['nome']); ?>">
                                    <label for="nome">Nome da Carta</label>
                                    <div class="invalid-feedback">Informe o nome da carta.</div>
                                </div>

                                <!-- Tipo -->
                                <div class="form-floating mt-3 mb-3">
                                    <select class="form-select" id="tipo" name="tipo" required>
                                        <?php
                                            $tipos = ['Água','Dragão','Elétrico','Fada','Fogo','Gelo',
                                                      'Incolor','Lutador','Metal','Planta','Psíquico','Sombrio'];
                                            foreach ($tipos as $t) {
                                                $selected = ($carta['tipo'] == $t) ? 'selected' : '';
                                                echo "<option value='$t' $selected>$t</option>";
                                            }
                                        ?>
                                    </select>
                                    <label for="tipo">Tipo do Pokémon</label>
                                    <div class="invalid-feedback">Selecione o tipo.</div>
                                </div>

                                <!-- Raridade -->
                                <div class="form-floating mt-3 mb-3">
                                    <select class="form-select" id="raridade" name="raridade" required>
                                        <?php
                                            $raridades = ['Comum','Incomum','Rara','Ultra Rara','Hiper Rara','Secreta'];
                                            foreach ($raridades as $r) {
                                                $selected = ($carta['raridade'] == $r) ? 'selected' : '';
                                                echo "<option value='$r' $selected>$r</option>";
                                            }
                                        ?>
                                    </select>
                                    <label for="raridade">Raridade</label>
                                    <div class="invalid-feedback">Selecione a raridade.</div>
                                </div>

                                <!-- Edição -->
                                <div class="form-floating mt-3 mb-3">
                                    <input type="text" class="form-control" id="edicao"
                                           placeholder="Edição" name="edicao"
                                           maxlength="50" required
                                           value="<?php echo htmlspecialchars($carta['edicao']); ?>">
                                    <label for="edicao">Edição / Coleção</label>
                                    <div class="invalid-feedback">Informe a edição da carta.</div>
                                </div>

                                <!-- Preço -->
                                <div class="form-floating mt-3 mb-3">
                                    <input type="number" class="form-control" id="preco"
                                           placeholder="Preço" name="preco"
                                           step="0.01" min="0.01" required
                                           value="<?php echo $carta['preco']; ?>">
                                    <label for="preco">Preço (R$)</label>
                                    <div class="invalid-feedback">Informe um preço válido.</div>
                                </div>

                                <!-- Estoque -->
                                <div class="form-floating mt-3 mb-3">
                                    <input type="number" class="form-control" id="estoque"
                                           placeholder="Estoque" name="estoque"
                                           min="0" required
                                           value="<?php echo $carta['estoque']; ?>">
                                    <label for="estoque">Quantidade em Estoque</label>
                                    <div class="invalid-feedback">Informe a quantidade em estoque.</div>
                                </div>

                                <!-- Nova Imagem (opcional) -->
                                <div class="form-floating mt-3 mb-4">
                                    <input type="file" class="form-control" id="imagem"
                                           placeholder="Nova Imagem" name="imagem">
                                    <label for="imagem">
                                        <i class="bi bi-image me-1"></i> Nova Imagem (opcional)
                                    </label>
                                    <small class="text-muted">Deixe em branco para manter a imagem atual.</small>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-warning fw-bold text-dark btn-lg flex-fill">
                                        <i class="bi bi-save me-1"></i> Salvar Alterações
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
