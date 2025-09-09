<?php 
include('../../includes/db.php');
include('../../includes/header.php'); 

// Filtros
$nomeFiltro = $_GET['nome'] ?? '';
$posicaoFiltro = $_GET['posicao'] ?? '';
$timeFiltro = $_GET['time'] ?? '';

// Paginação
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$itens_por_pagina = 10;
$offset = ($pagina - 1) * $itens_por_pagina;

/* =======================================================
   CONTAGEM DE REGISTROS (com filtros aplicados)
======================================================= */
$sqlCount = "SELECT COUNT(*) as total 
             FROM jogadores j
             LEFT JOIN times t ON j.time_id = t.id
             WHERE 1=1";

$paramsCount = [];
$typesCount = "";

// Filtro por nome
if (!empty($nomeFiltro)) {
    $sqlCount .= " AND j.nome LIKE ?";
    $paramsCount[] = "%$nomeFiltro%";
    $typesCount .= "s";
}

// Filtro por posição
if (!empty($posicaoFiltro)) {
    $sqlCount .= " AND j.posicao = ?";
    $paramsCount[] = $posicaoFiltro;
    $typesCount .= "s";
}

// Filtro por time
if (!empty($timeFiltro)) {
    $sqlCount .= " AND t.nome LIKE ?";
    $paramsCount[] = "%$timeFiltro%";
    $typesCount .= "s";
}

$stmtCount = $mysqli->prepare($sqlCount);
if ($paramsCount) {
    $stmtCount->bind_param($typesCount, ...$paramsCount);
}
$stmtCount->execute();
$total_registros = $stmtCount->get_result()->fetch_assoc()['total'];
$total_paginas = max(1, ceil($total_registros / $itens_por_pagina));

$sql = "SELECT j.id, j.nome, j.posicao, j.numero_camisa, t.nome AS nome_time
        FROM jogadores j
        LEFT JOIN times t ON j.time_id = t.id
        WHERE 1=1";

$params = [];
$types = "";

// Filtro por nome
if (!empty($nomeFiltro)) {
    $sql .= " AND j.nome LIKE ?";
    $params[] = "%$nomeFiltro%";
    $types .= "s";
}

// Filtro por posição
if (!empty($posicaoFiltro)) {
    $sql .= " AND j.posicao = ?";
    $params[] = $posicaoFiltro;
    $types .= "s";
}

// Filtro por time
if (!empty($timeFiltro)) {
    $sql .= " AND t.nome LIKE ?";
    $params[] = "%$timeFiltro%";
    $types .= "s";
}

// Ordenação + paginação (mais recentes primeiro)
$sql .= " ORDER BY id ASC LIMIT ?, ?";
$params[] = $offset;
$params[] = $itens_por_pagina;
$types .= "ii";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="d-flex justify-content-between mb-3">
    <h2>Jogadores</h2>
    <a class="btn btn-success" href="create.php">➕ Adicionar Jogador</a>
</div>

<!-- Formulário de Filtros -->
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="text" name="nome" value="<?= htmlspecialchars($nomeFiltro) ?>" class="form-control" placeholder="Filtrar por nome">
    </div>
    <div class="col-md-3">
        <select name="posicao" class="form-control">
            <option value="">Todas as posições</option>
            <option value="GOL" <?= $posicaoFiltro=="GOL" ? "selected" : "" ?>>Goleiro</option>
            <option value="ZAG" <?= $posicaoFiltro=="ZAG" ? "selected" : "" ?>>Zagueiro</option>
            <option value="LAT" <?= $posicaoFiltro=="LAT" ? "selected" : "" ?>>Lateral</option>
            <option value="MEI" <?= $posicaoFiltro=="MEI" ? "selected" : "" ?>>Meio-campo</option>
            <option value="ATA" <?= $posicaoFiltro=="ATA" ? "selected" : "" ?>>Atacante</option>
        </select>
    </div>
    <div class="col-md-3">
        <input type="text" name="time" value="<?= htmlspecialchars($timeFiltro) ?>" class="form-control" placeholder="Filtrar por time">
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
    </div>
</form>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Posição</th>
            <th>Número</th>
            <th>Time</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nome']) ?></td>
                    <td><?= htmlspecialchars($row['posicao']) ?></td>
                    <td><?= htmlspecialchars($row['numero_camisa']) ?></td>
                    <td><?= $row['nome_time'] ?? '—' ?></td>
                    <td>
                        <a class="btn btn-sm btn-warning" href="update.php?id=<?= $row['id'] ?>">Editar</a>
                        <a class="btn btn-sm btn-danger" href="delete.php?id=<?= $row['id'] ?>" 
                           onclick="return confirm('Deseja realmente excluir este jogador?')">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6" class="text-center">Nenhum jogador encontrado.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Paginação -->
<nav>
    <ul class="pagination">
        <?php for($i=1; $i<=$total_paginas; $i++): ?>
            <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                <a class="page-link" 
                   href="?pagina=<?= $i ?>&nome=<?= urlencode($nomeFiltro) ?>&posicao=<?= urlencode($posicaoFiltro) ?>&time=<?= urlencode($timeFiltro) ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>

<?php include('../../includes/footer.php'); ?>
<link rel="stylesheet" href="../../style/style.css">