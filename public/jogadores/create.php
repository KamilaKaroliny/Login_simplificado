<?php 
include('../../includes/db.php');
include('../../includes/header.php'); 
?>

<div class="container mt-4">
    <h2 class="mb-4">Cadastrar Jogador</h2>

    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="posicao" class="form-label">Posição</label>
            <select name="posicao" class="form-select" required>
                <option value="GOL">Goleiro</option>
                <option value="ZAG">Zagueiro</option>
                <option value="LAT">Lateral</option>
                <option value="MEI">Meio-campo</option>
                <option value="ATA">Atacante</option>
            </select>
        </div>

        <div class="col-md-6">
            <label for="numero_camisa" class="form-label">Número da Camisa</label>
            <input type="number" name="numero_camisa" id="numero_camisa" class="form-control" min="1" max="99" required>
        </div>

        <div class="col-md-6">
            <label for="time_id" class="form-label">Time</label>
            <select name="time_id" class="form-select" required>
                <?php
                $result = $conn->query("SELECT id, nome FROM times");
                while($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                }
                ?>
            </select>
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
    $posicao = $_POST['posicao'];
    $numero_camisa = $_POST['numero_camisa'];
    $time_id = $_POST['time_id'];

    $sql = "INSERT INTO jogadores (nome, posicao, numero_camisa, time_id)
            VALUES ('$nome', '$posicao', $numero_camisa, $time_id)";

    if ($conn->query($sql)) {
        header("Location: read.php");
        exit;
    } else {
        echo "<div class='alert alert-danger mt-3'>Erro: " . $conn->error . "</div>";
    }
}
?>