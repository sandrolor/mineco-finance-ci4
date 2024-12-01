<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2>Nova Categoria</h2>
    <form method="post" action="<?= site_url('categorias/store') ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="nomecategoria" class="form-label">Nome da Categoria</label>
            <input type="text" class="form-control" id="nomecategoria" name="nomecategoria" required>
        </div>
        <div class="mb-3">
            <label for="grupo_id" class="form-label">Grupo</label>
            <select class="form-control" id="grupo_id" name="grupo_id" required>
                <option value="">Selecione</option>
                <?php foreach ($grupos as $grupo): ?>
                    <option value="<?= esc($grupo['id']) ?>"><?= esc($grupo['nomegrupo']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
    </form>
</div>

<?= $this->endSection() ?>