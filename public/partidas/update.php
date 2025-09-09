<?php
include('../../includes/db.php');
include('../../includes/header.php');

$erro = "";

if (!isset($_GET['id'])) {
    echo "<p>ID da partida não informado.</p>";
    include('../../includes/footer.php');
    exit;
}

$id = $_GET['id'];

// Busca a partida no banco
$stmt = $mysqli->prepare("SELECT * FROM partidas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$partida = $result->fetch_assoc();

if (!$partida) {
    echo "<p>Partida não encontrada.</p>";
    include('../../includes/footer.php');
    exit;
}

// Busca todos os times para preencher os selects
$times = $mysqli->query("SELECT * FROM times")->fetch_all(MYSQLI_ASSOC);

// Se enviou o formulário (POST)
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
        $stmt = $mysqli->prepare("UPDATE partidas 
            SET time_casa_id=?, time_fora_id=?, data_jogo=?, gols_casa=?, gols_fora=? 
            WHERE id=?");
        $stmt->bind_param("iisiii", $time_casa_id, $time_fora_id, $data_jogo, $gols_casa, $gols_fora, $id);
        $stmt->execute();
        header("Location: read.php");
        exit;
    }
}
?>

<h2>Editar Partida</h2>

<?php if($erro): ?>
<div class="alert alert-danger"><?= $erro ?></div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label class="form-label">Mandante</label>
        <select name="time_casa_id" class="form-select">
            <?php foreach($times as $t): ?>
                <option value="<?= $t['id'] ?>" <?= $t['id'] == $partida['time_casa_id'] ? 'selected' : '' ?>>
                    <?= $t['nome'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Visitante</label>
        <select name="time_fora_id" class="form-select">
            <?php foreach($times as $t): ?>
                <option value="<?= $t['id'] ?>" <?= $t['id'] == $partida['time_fora_id'] ? 'selected' : '' ?>>
                    <?= $t['nome'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Data do Jogo</label>
        <input type="date" name="data_jogo" class="form-control" 
               value="<?= $partida['data_jogo'] ?>" required>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Gols Casa</label>
            <input type="number" name="gols_casa" class="form-control" 
                   min="0" value="<?= $partida['gols_casa'] ?>">
        </div>
        <div class="col">
            <label class="form-label">Gols Fora</label>
            <input type="number" name="gols_fora" class="form-control" 
                   min="0" value="<?= $partida['gols_fora'] ?>">
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Atualizar <a href="read.php"></a></button> 
    <a href="read.php" class="btn btn-secondary">Cancelar</a>
</form>