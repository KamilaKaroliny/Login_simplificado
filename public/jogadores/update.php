<?php 
include('../../includes/db.php');
include('../../includes/header.php'); 

$id = $_GET['id'];
$dados = $conn->query("SELECT * FROM jogadores WHERE id=$id")->fetch_assoc();
?>

<div class="container mt-4">
    <h2 class="mb-4">Editar Jogador</h2>

    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" id="nome" value="<?= $dados['nome'] ?>" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="posicao" class="form-label">Posição</label>
            <select name="posicao" class="form-select">
                <option value="GOL" <?= $dados['posicao']=="GOL"?"selected":"" ?>>Goleiro</option>
                <option value="ZAG" <?= $dados['posicao']=="ZAG"?"selected":"" ?>>Zagueiro</option>
                <option value="LAT" <?= $dados['posicao']=="LAT"?"selected":"" ?>>Lateral</option>
                <option value="MEI" <?= $dados['posicao']=="MEI"?"selected":"" ?>>Meio-campo</option>
                <option value="ATA" <?= $dados['posicao']=="ATA"?"selected":"" ?>>Atacante</option>
            </select>
        </div>

        <div class="col-md-6">
            <label for="numero_camisa" class="form-label">Número da Camisa</label>
            <input type="number" name="numero_camisa" value="<?= $dados['numero_camisa'] ?>" min="1" max="99" class="form-control">
        </div>

        <div class="col-md-6">
            <label for="time_id" class="form-label">Time</label>
            <select name="time_id" class="form-select">
                <?php
                $result = $conn->query("SELECT id, nome FROM times");
                while($row = $result->fetch_assoc()) {
                    $selected = $row['id'] == $dados['time_id'] ? "selected" : "";
                    echo "<option value='{$row['id']}' $selected>{$row['nome']}</option>";
                }
                ?>
            </select>
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
    $posicao = $_POST['posicao'];
    $numero_camisa = $_POST['numero_camisa'];
    $time_id = $_POST['time_id'];

    $sql = "UPDATE jogadores 
            SET nome='$nome', posicao='$posicao', numero_camisa=$numero_camisa, time_id=$time_id 
            WHERE id=$id";

    if ($conn->query($sql)) {
        header("Location: read.php");
        exit;
    } else {
        echo "<div class='alert alert-danger mt-3'>Erro: " . $conn->error . "</div>";
    }
}
?>