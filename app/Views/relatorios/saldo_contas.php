<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2>Relatório de Saldo das Contas</h2>

    <!-- Filtro de Data -->
    <form method="get" class="row g-3 my-3">
        <?= csrf_field() ?>
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

    <!-- Tabela de Saldos -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Conta</th>
                <th>Saldo (R$)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($saldos)): ?>

                <?php foreach ($saldos as $saldo): ?>
                    <tr>

                        <td><?= esc($saldo['nome_conta']) ?></td>
                        <td><?= number_format($saldo['saldo'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">Nenhum registro encontrado no período selecionado.</td>
                </tr>
            <?php endif; ?>
            // No final da tabela (saldo_contas.php):
            <tr class="table-primary">
                <td><strong>Total</strong></td>
                <td><strong><?= number_format(array_sum(array_column($saldos, 'saldo')), 2, ',', '.') ?></strong></td>
            </tr>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>