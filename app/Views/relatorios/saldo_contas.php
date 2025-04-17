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
                <th>Saldo Anterior (R$)</th>
                <th>Movimento (R$)</th>
                <th>Saldo Atual (R$)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dados_organizados as $grupo => $dados): ?>
                <!-- Subtotal do Grupo -->
                <tr class="table-secondary">
                    <td><strong><?= esc($grupo) ?></strong></td>
                    <td><strong><?= number_format($dados['subtotais']['saldo_anterior'], 2, ',', '.') ?></strong></td>
                    <td><strong><?= number_format($dados['subtotais']['movimento'], 2, ',', '.') ?></strong></td>
                    <td><strong><?= number_format($dados['subtotais']['saldo_atual'], 2, ',', '.') ?></strong></td>
                </tr>
                <!-- Contas do Grupo -->
                <?php foreach ($dados['contas'] as $conta): ?>
                    <tr>
                        <td><?= esc($conta['nome_conta']) ?></td>
                        <td><?= number_format($conta['saldo_anterior'], 2, ',', '.') ?></td>
                        <td><?= number_format($conta['movimento'], 2, ',', '.') ?></td>
                        <td><?= number_format($conta['saldo_atual'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <!-- Total Geral -->
            <tr class="table-primary">
                <td><strong>Total Geral</strong></td>
                <td><strong><?= number_format($total_geral['saldo_anterior'], 2, ',', '.') ?></strong></td>
                <td><strong><?= number_format($total_geral['movimento'], 2, ',', '.') ?></strong></td>
                <td><strong><?= number_format($total_geral['saldo_atual'], 2, ',', '.') ?></strong></td>
            </tr>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>