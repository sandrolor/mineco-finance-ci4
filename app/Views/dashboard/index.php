<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

    <h2 class="text-center">Bem-vindo, <?= session('username') ?>!</h2>

<?= $this->endSection() ?>