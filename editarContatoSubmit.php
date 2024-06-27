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
    if(isset($_FILES['foto'])){
        $foto = $_FILES['foto'];
    }else{
        $foto = array();
    }

    if(!empty($email)){
        $contato->editar($nome, $email, $telefone, $cidade, $rua, $numero, $bairro, $cep, $profissao, $foto, $dt_nasc, $_GET['id']);
    }
    header("Location: /agendaSenac");
}
if(isset($_GET['id']) && !empty($_GET['id'])){
    $info = $contato->getContato($_GET['id']);
}else{
    ?>
    <script type="text/javascript">window.location.href="index.php";</script>
    <?php
    exit;
}
