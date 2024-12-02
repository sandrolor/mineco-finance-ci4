<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<h1>Movimentos</h1>

<form method="get" action="<?= site_url('movimento') ?>" class="mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-3">
            <label for="data_inicial" class="form-label">Data Inicial</label>
            <input type="date" id="data_inicial" name="data_inicial" class="form-control" value="<?= esc($dataInicial) ?>">
        </div>
        <div class="col-md-3">
            <label for="data_final" class="form-label">Data Final</label>
            <input type="date" id="data_final" name="data_final" class="form-control" value="<?= esc($dataFinal) ?>">
        </div>
        <div class="col-md-3">
            <label for="conta_id" class="form-label">Conta</label>
            <select id="conta_id" name="conta_id" class="form-select">
                <option value="">Todas as contas</option>
                <?php foreach ($contas as $conta): ?>
                    <option value="<?= esc($conta['id']) ?>" <?= $contaSelecionada == $conta['id'] ? 'selected' : '' ?>>
                        <?= esc($conta['nomeconta']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label for="categoria_id" class="form-label">Categoria</label>
            <select id="categoria_id" name="categoria_id" class="form-select">
                <option value="">Todas as categorias</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= esc($categoria['id']) ?>" <?= $categoriaSelecionada == $categoria['id'] ? 'selected' : '' ?>>
                        <?= esc($categoria['nomecategoria']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
    </div>
</form>

<?php if ($movimentos): ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Data</th>
                <th>Histórico</th>
                <th>Conta</th>
                <th>Categoria</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movimentos as $movimento): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($movimento['data_mov'])) ?></td>
                    <td><?= esc($movimento['historico']) ?></td>
                    <td><?= esc($movimento['nomeconta']) ?></td>
                    <td><?= esc($movimento['nomecategoria']) ?></td>
                    <td class="text-end"><?= number_format($movimento['valor'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nenhum movimento encontrado.</p>
<?php endif; ?>

<?= $this->endSection() ?>