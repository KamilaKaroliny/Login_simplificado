<?php
include('../../includes/db.php');
include('../../includes/header.php');

// Busca todos os times para preencher os selects
$times = $conn->query("SELECT * FROM times ORDER BY nome")->fetch_all(MYSQLI_ASSOC);

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $time_casa_id = $_POST['time_casa_id'];
    $time_fora_id = $_POST['time_fora_id'];
    $data_jogo = $_POST['data_jogo'];
    $gols_casa = $_POST['gols_casa'];
    $gols_fora = $_POST['gols_fora'];

    // VALIDAÇÃO: Mandante ≠ Visitante
    if ($time_casa_id == $time_fora_id) {
        $erro = "O time mandante não pode ser igual ao time visitante!";
    } else {
        $stmt = $conn->prepare("INSERT INTO partidas 
            (time_casa_id, time_fora_id, data_jogo, gols_casa, gols_fora) 
            VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisii", $time_casa_id, $time_fora_id, $data_jogo, $gols_casa, $gols_fora);
        $stmt->execute();
        header("Location: read.php");
        exit;
    }
}
?>

<h2>Cadastrar Partida</h2>

<?php if($erro): ?>
<div class="alert alert-danger"><?= $erro ?></div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label class="form-label">Mandante</label>
        <select name="time_casa_id" class="form-select">
            <?php foreach($times as $t): ?>
            <option value="<?= $t['id'] ?>"><?= $t['nome'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Visitante</label>
        <select name="time_fora_id" class="form-select">
            <?php foreach($times as $t): ?>
            <option value="<?= $t['id'] ?>"><?= $t['nome'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Data do Jogo</label>
        <input type="date" name="data_jogo" class="form-control" required>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Gols Casa</label>
            <input type="number" name="gols_casa" class="form-control" min="0" value="0">
        </div>
        <div class="col">
            <label class="form-label">Gols Fora</label>
            <input type="number" name="gols_fora" class="form-control" min="0" value="0">
        </div>
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="read.php" class="btn btn-secondary">Cancelar</a>
</form>