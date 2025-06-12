<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2>Relatório de Resultado por Categorias</h2>

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

    <!-- Tabela de Resultados -->
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Categoria</th>
                <th>Receitas (R$)</th>
                <th>Despesas (R$)</th>
                <th>Resultado (R$)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dados_organizados as $grupo => $dados): ?>
                <!-- Subtotal do Grupo -->
                <tr class="table-secondary">
                    <td><strong><?= esc($grupo) ?></strong></td>
                    <td><strong><?= number_format($dados['subtotais']['receitas'], 2, ',', '.') ?></strong></td>
                    <td><strong><?= number_format($dados['subtotais']['despesas'], 2, ',', '.') ?></strong></td>
                    <td><strong><?= number_format($dados['subtotais']['saldo'], 2, ',', '.') ?></strong></td>
                </tr>
                <!-- Categorias do Grupo -->
                <?php foreach ($dados['categorias'] as $categoria): ?>
                    <tr>
                        <td><?= esc($categoria['nome_categoria']) ?></td>
                        <td><?= number_format($categoria['receitas'], 2, ',', '.') ?></td>
                        <td><?= number_format($categoria['despesas'], 2, ',', '.') ?></td>
                        <td><?= number_format($categoria['saldo'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <!-- Total Geral -->
            <tr class="table-primary">
                <td><strong>Total Geral</strong></td>
                <td><strong><?= number_format($total_geral['receitas'], 2, ',', '.') ?></strong></td>
                <td><strong><?= number_format($total_geral['despesas'], 2, ',', '.') ?></strong></td>
                <td><strong><?= number_format($total_geral['saldo'], 2, ',', '.') ?></strong></td>
            </tr>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>