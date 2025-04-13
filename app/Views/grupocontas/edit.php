<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2>Editar Grupo de Contas</h2>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <?= $error ?><br>
            <?php endforeach ?>
        </div>
    <?php endif; ?>
    <form method="post" action="<?= site_url('grupocontas/update/' . $grupo['id']) ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="nomegrupo" class="form-label">Nome do Grupo</label>
            <input type="text" class="form-control" id="nomegrupo" name="nomegrupo" value="<?= esc($grupo['nomegrupo']) ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="<?= site_url('grupocontas') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?= $this->endSection() ?>