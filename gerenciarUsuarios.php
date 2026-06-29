<?php include "header.php" ?>

<?php include "conexaoBD.php" ?>

<?php
    // Verifica se o usuário está logado e é administrador
    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || $_SESSION['tipoUsuario'] != 'administrador') {
        header("Location: formLogin.php");
        exit();
    }

    $msgFeedback = $_GET['msg'] ?? '';
?>

<section class="py-5">
    <div class="container mt-3">

        <h3 class="fw-bolder mb-4">
            <i class="bi bi-people me-2"></i> Gerenciar Usuários
        </h3>

        <?php if ($msgFeedback == 'removido'): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-1'></i> Usuário removido com sucesso!
            </div>
        <?php endif; ?>

        <?php
            // Query para listar todos os usuários
            $listarUsuarios = "SELECT * FROM usuario ORDER BY nome ASC";
            $res            = mysqli_query($conn, $listarUsuarios) or die("Erro ao listar usuários!");
            $totalUsuarios  = mysqli_num_rows($res);

            echo "<div class='alert alert-info text-center'>
                    Há <strong>$totalUsuarios</strong> usuário(s) cadastrado(s) no sistema.
                  </div>";
        ?>

        <!-- Tabela de Usuários -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>NOME</th>
                        <th>E-MAIL</th>
                        <th>TIPO</th>
                        <th>DATA DE CADASTRO</th>
                        <th class="text-center">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($usuario = mysqli_fetch_assoc($res)): ?>
                    <tr>
                        <td><?php echo $usuario['id_usuario']; ?></td>
                        <td class="fw-bold"><?php echo htmlspecialchars($usuario['nome']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td>
                            <?php if ($usuario['tipo'] == 'administrador'): ?>
                                <span class="badge bg-danger">
                                    <i class="bi bi-shield-fill me-1"></i> Administrador
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">
                                    <i class="bi bi-person me-1"></i> Cliente
                                </span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($usuario['data_cadastro'])); ?></td>
                        <td class="text-center">
                            <?php if ($usuario['id_usuario'] != $_SESSION['idUsuario']): ?>
                                <!-- Não permite remover o próprio usuário logado -->
                                <a href="actionRemoverUsuario.php?id_usuario=<?php echo $usuario['id_usuario']; ?>"
                                   class="btn btn-sm btn-danger"
                                   title="Remover usuário"
                                   onclick="return confirm('Tem certeza que deseja remover o usuário \'<?php echo htmlspecialchars($usuario['nome']); ?>\'?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            <?php else: ?>
                                <span class="text-muted small">Usuário atual</span>
                            <?php endif; ?>
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
