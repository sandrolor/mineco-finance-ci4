<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2>Nova Conta</h2>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <?= $error ?><br>
            <?php endforeach ?>
        </div>
    <?php endif; ?>
    <form method="post" action="<?= site_url('contas/store') ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="nomeconta" class="form-label">Nome da Conta</label>
            <input type="text" class="form-control" id="nomeconta" name="nomeconta" required>
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