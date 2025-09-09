<?php
include('../../includes/db.php');
include('../../includes/header.php');

// Filtros
$timeFiltro = $_GET['time'] ?? '';
$dataInicio = $_GET['data_inicio'] ?? '';
$dataFim = $_GET['data_fim'] ?? '';

// Paginação
$pagina = $_GET['pagina'] ?? 1;
$itens_por_pagina = 10;
$offset = ($pagina - 1) * $itens_por_pagina;

// Contar total de registros com filtros
$sqlCount = "SELECT COUNT(*) as total FROM partidas p
             JOIN times tc ON p.time_casa_id = tc.id
             JOIN times tf ON p.time_fora_id = tf.id
             WHERE 1=1";

$paramsCount = [];
$typesCount = "";

// Filtro por time
if ($timeFiltro) {
    $sqlCount .= " AND (tc.nome LIKE ? OR tf.nome LIKE ?)";
    $paramsCount[] = "%$timeFiltro%";
    $paramsCount[] = "%$timeFiltro%";
    $typesCount .= "ss";
}

// Filtro por período
if ($dataInicio) {
    $sqlCount .= " AND p.data_jogo >= ?";
    $paramsCount[] = $dataInicio;
    $typesCount .= "s";
}

$stmtCount = $conn->prepare($sqlCount);
if (!empty($paramsCount)) {
    $stmtCount->bind_param($typesCount, ...$paramsCount);
}
$stmtCount->execute();
$total_registros = $stmtCount->get_result()->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $itens_por_pagina);

// Consulta principal com filtros e paginação
$sql = "SELECT p.*, tc.nome as time_casa, tf.nome as time_fora 
        FROM partidas p
        JOIN times tc ON p.time_casa_id = tc.id
        JOIN times tf ON p.time_fora_id = tf.id
        WHERE 1=1";

$params = [];
$types = "";

// Filtro por time
if ($timeFiltro) {
    $sql .= " AND (tc.nome LIKE ? OR tf.nome LIKE ?)";
    $params[] = "%$timeFiltro%";
    $params[] = "%$timeFiltro%";
    $types .= "ss";
}

// Filtro por período
if ($dataInicio) {
    $sql .= " AND p.data_jogo >= ?";
    $params[] = $dataInicio;
    $types .= "s";
}

// Limite para paginação
$sql .= " ORDER BY p.data_jogo DESC LIMIT ?, ?";
$params[] = $offset;
$params[] = $itens_por_pagina;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="d-flex justify-content-between mb-3">
    <h2>Partidas dos próximos jogos - Brasileirão Série A</h2>
    <a class="btn btn-success" href="create.php">➕ Cadastrar Partida</a>
</div>

<form class="row g-3 mb-3" method="get">
    <div class="col-md-4">
        <input type="text" name="time" class="form-control" placeholder="Filtro por Time" value="<?= htmlspecialchars($timeFiltro) ?>">
    </div>
    <div class="col-md-3">
        <input type="date" name="data_inicio" class="form-control" value="<?= $dataInicio ?>">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
    </div>
</form>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Mandante</th>
            <th>Visitante</th>
            <th>Data</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['time_casa'] ?></td>
            <td><?= $row['time_fora'] ?></td>
            <td><?= $row['data_jogo'] ?></td>
            <td>
                <a class="btn btn-sm btn-warning" href="update.php?id=<?= $row['id'] ?>">Editar</a>
                <a class="btn btn-sm btn-danger" href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Deseja realmente excluir?')">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Paginação -->
<nav>
  <ul class="pagination">
    <?php for($i = 1; $i <= $total_paginas; $i++): ?>
      <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
        <a class="page-link" href="?pagina=<?= $i ?>&time=<?= urlencode($timeFiltro) ?>&data_inicio=<?= $dataInicio ?>&data_fim=<?= $dataFim ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>
  </ul>
</nav>

<?php include('../../includes/footer.php'); ?>
<link rel="stylesheet" href="../../style/style.css">