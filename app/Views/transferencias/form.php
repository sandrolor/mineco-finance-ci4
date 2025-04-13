<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2><?= isset($transferencia) ? 'Editar Transferência' : 'Nova Transferência' ?></h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= isset($transferencia) ? site_url('transferencias/update/' . $transferencia['id']) : site_url('transferencias/store') ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="data_mov" class="form-label">Data</label>
            <input type="date" name="data_mov" class="form-control" value="<?= isset($transferencia) ? $transferencia['data_mov'] : '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="conta_origem" class="form-label">Conta Origem</label>
            <select name="conta_origem" class="form-control" required>
                <?php foreach ($contas as $conta): ?>
                    <option value="<?= $conta['id'] ?>" <?= isset($transferencia) && $transferencia['conta_id'] == $conta['id'] ? 'selected' : '' ?>>
                        <?= esc($conta['nomeconta']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="conta_destino" class="form-label">Conta Destino</label>
            <select name="conta_destino" class="form-control" required>
                <?php foreach ($contas as $conta): ?>
                    <option value="<?= $conta['id'] ?>" <?= isset($transferencia) && $transferencia['conta_destino_id'] == $conta['id'] ? 'selected' : '' ?>>
                        <?= esc($conta['nomeconta']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="valor" class="form-label">Valor</label>
            <input type="number" step="0.01" name="valor" class="form-control" value="<?= isset($transferencia) ? abs($transferencia['valor']) : '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="historico" class="form-label">Histórico</label>
            <textarea name="historico" class="form-control" rows="3"><?= isset($transferencia) ? esc($transferencia['historico']) : '' ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><?= isset($transferencia) ? 'Atualizar' : 'Salvar' ?></button>
    </form>
</div>

<?= $this->endSection() ?>