<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2>Editar Categoria</h2>

    <form action="<?= site_url('categorias/update/' . $categoria['id']) ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="nomecategoria" class="form-label">Nome da Categoria</label>
            <input type="text" class="form-control" id="nomecategoria" name="nomecategoria" value="<?= esc($categoria['nomecategoria']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="grupo_id" class="form-label">Grupo</label>
            <select class="form-select" id="grupo_id" name="grupo_id" required>
                <option value="">Selecione um grupo</option>
                <?php foreach ($grupos as $grupo): ?>
                    <option value="<?= $grupo['id'] ?>" <?= $grupo['id'] == $categoria['grupo_id'] ? 'selected' : '' ?>>
                        <?= esc($grupo['nomegrupo']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="<?= site_url('categorias') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?= $this->endSection() ?>