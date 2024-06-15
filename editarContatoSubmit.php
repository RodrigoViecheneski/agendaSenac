<?php
include 'classes/contatos.class.php';
$contato = new Contatos();

if(!empty($_POST['id'])){
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cidade = $_POST['cidade'];
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $cep = $_POST['cep'];
    $profissao = $_POST['profissao'];
    $dt_nasc = $_POST['dt_nasc'];
    $id = $_POST['id'];
    if(!empty($email)){
        $contato->editar($nome, $email, $telefone, $cidade, $rua, $numero, $bairro, $cep, $profissao, $dt_nasc, $id);
    }
    header("Location: /agendaSenac");
}
