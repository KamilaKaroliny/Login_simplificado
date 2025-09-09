<?php 
include('../../includes/db.php');
include('../../includes/header.php'); 

$id = $_GET['id'];
$dados = $conn->query("SELECT * FROM times WHERE id=$id")->fetch_assoc();
?>

<div class="container mt-4">
    <h2 class="mb-4">Editar Time</h2>

    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label for="nome" class="form-label">Nome do Time</label>
            <input type="text" name="nome" id="nome" value="<?= $dados['nome'] ?>" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="cidade" class="form-label">Cidade</label>
            <input type="text" name="cidade" id="cidade" value="<?= $dados['cidade'] ?>" class="form-control" required>
        </div>

        <div class="col-12">
            <button type="submit" name="atualizar" class="btn btn-primary">Atualizar</button>
            <a href="read.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php
if (isset($_POST['atualizar'])) {
    $nome = $_POST['nome'];
    $cidade = $_POST['cidade'];

    $sql = "UPDATE times SET nome='$nome', cidade='$cidade' WHERE id=$id";

    if ($conn->query($sql)) {
        header("Location: read.php");
        exit;
    } else {
        echo "<div class='alert alert-danger mt-3'>Erro: " . $conn->error . "</div>";
    }
}
?>