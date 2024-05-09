<?php
session_start();
//require_once 'inc/cabecalho.inc.php';
require 'classes/usuarios.class.php';

if(!empty($_POST['email'])){
    $email = addslashes($_POST['email']);
    $senha = md5($_POST['senha']);

    $usuarios = new Usuarios();
    if($usuarios->fazerLogin($email, $senha)){
        header("Location: index.php");
        exit;
    }else{
        echo '<span style="color: green">'."Usuário e/ou senha incorretos!". '</span>';
    }
}
?>
<h1>LOGIN</h1>
<form method="POST">
    Email: <br>
    <input type="email" name="email"><br><br>
    Senha: <br>
    <input type="password" name="senha"><br><br>
    <input type="submit" value="Entrar">
</form>