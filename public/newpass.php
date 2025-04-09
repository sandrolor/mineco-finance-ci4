<?php //echo password_hash('senha', PASSWORD_DEFAULT); ?>

<?php
if (isset($_GET['senha'])) {
    $senha = $_GET['senha'];
    echo password_hash($senha, PASSWORD_DEFAULT);
} else {
    echo "Informe a senha na URL, exemplo: ?senha=minhaSenha";
}
?>

<!-- http://localhost:8282/mineco-finance-ci4/public/newpass.php?senha=admin -->