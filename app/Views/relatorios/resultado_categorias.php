<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2>Relatório de Resultado por Categorias</h2>

    <!-- Filtro de Data -->
    <form method="get" class="row g-3 my-3">
        <div class="col-md-4">
            <label for="start_date" class="form-label">Data Inicial</label>
            <input type="date" id="start_date" name="start_date" class="form-control" value="<?= esc($startDate) ?>">
        </div>
        <div class="col-md-4">
            <label for="end_date" class="form-label">Data Final</label>
            <input type="date" id="end_date" name="end_date" class="form-control" value="<?= esc($endDate) ?>">
        </div>
        <div class="col-md-4 align-self-end">
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </form>

    <!-- Tabela de Resultados -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Categoria</th>
                <th>Receitas (R$)</th>
                <th>Despesas (R$)</th>
                <th>Saldo (R$)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($resultados)): ?>
                <?php foreach ($resultados as $resultado): ?>
                    <tr>
                        <td><?= esc($resultado['nome_categoria']) ?></td>
                        <td><?= number_format($resultado['receitas'], 2, ',', '.') ?></td>
                        <td><?= number_format($resultado['despesas'], 2, ',', '.') ?></td>
                        <td><?= number_format($resultado['saldo'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Nenhum registro encontrado no período selecionado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tr class="table-primary">
            <td><strong>Total</strong></td>
            <td><strong><?= number_format(array_sum(array_column($resultados, 'receitas')), 2, ',', '.') ?></strong></td>
            <td><strong><?= number_format(array_sum(array_column($resultados, 'despesas')), 2, ',', '.') ?></strong></td>
            <td><strong><?= number_format(array_sum(array_column($resultados, 'saldo')), 2, ',', '.') ?></strong></td>
        </tr>
    </table>
</div>

<?= $this->endSection() ?>