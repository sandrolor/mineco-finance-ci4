<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<h2>Editar Conta</h2>

<form action="<?= site_url('contas/update/' . $conta['id']) ?>" method="post">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label for="nomeconta" class="form-label">Nome da Conta</label>
        <input type="text" class="form-control" id="nomeconta" name="nomeconta" value="<?= esc($conta['nomeconta']) ?>" required>
    </div>
    
    <div class="mb-3">
        <label for="grupo_id" class="form-label">Grupo</label>
        <select class="form-select" id="grupo_id" name="grupo_id" required>
            <option value="">Selecione um grupo</option>
            <?php foreach ($grupos as $grupo): ?>
                <option value="<?= $grupo['id'] ?>" <?= $grupo['id'] == $conta['grupo_id'] ? 'selected' : '' ?>>
                    <?= esc($grupo['nomegrupo']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="<?= site_url('contas') ?>" class="btn btn-secondary">Cancelar</a>
</form>

<?= $this->endSection() ?>