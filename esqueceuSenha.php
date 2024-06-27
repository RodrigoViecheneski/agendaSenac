<?php
session_start();
//require_once 'inc/cabecalho.inc.php';
require 'classes/usuarios.class.php';

if(!empty($_POST['email'])){
    $email = addslashes($_POST['email']);
    $senha = md5($_POST['senha']);
    $senha2 = md5($_POST['senha2']);

    $usuarios = new Usuarios();
    if($senha == $senha2 && $usuarios->alterarSenha($email, $senha)){
        header("Location: index.php");
        exit;
    }else{
        echo '<span style="color: green">'."Usuário e/ou senha incorretos!". '</span>';
    }
}
?>



<h1>Esqueceu sua senha?</h1>

<form method="POST">
    Informe seu login (Email):<br>
    <input type="email" name="email"><br><br>
    Digite a nova senha: <br>
    <input type="password" name="senha"><br><br>
    Repita a nova senha: <br>
    <input type="password" name="senha2"><br><br>
    <input type="submit" value="Alterar">