<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2>Editar Grupo de Categorias</h2>
    <form method="post" action="<?= site_url('grupocategorias/update/' . $grupo['id']) ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="nomegrupo" class="form-label">Nome do Grupo</label>
            <input type="text" class="form-control" id="nomegrupo" name="nomegrupo" value="<?= esc($grupo['nomegrupo']) ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="<?= site_url('grupocategorias') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?= $this->endSection() ?>