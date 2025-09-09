<?php 
include('../../includes/db.php');
include('../../includes/header.php'); 
?>

<div class="container mt-4">
    <h2 class="mb-4">Cadastrar Time</h2>

    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label for="nome" class="form-label">Nome do Time</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="cidade" class="form-label">Cidade</label>
            <input type="text" name="cidade" id="cidade" class="form-control" required>
        </div>

        <div class="col-12">
            <button type="submit" name="salvar" class="btn btn-success">Salvar</button>
            <a href="read.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php
if (isset($_POST['salvar'])) {
    $nome = $_POST['nome'];
    $cidade = $_POST['cidade'];

    $sql = "INSERT INTO times (nome, cidade) VALUES ('$nome', '$cidade')";

    if ($mysqli->query($sql)) {
        header("Location: read.php");
        exit;
    } else {
        echo "<div class='alert alert-danger mt-3'>Erro: " . $mysqli->error . "</div>";
    }
}
?>