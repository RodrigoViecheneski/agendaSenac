<?php
    include 'classes/contatos.class.php';
    $contato = new Contatos();

    if(!empty($_GET['id'])){
        $id = $_GET['id'];
        $info = $contato->buscar($id);
        if(empty($info['email'])){
            header("Location: /agendaSenac");
            exit;
        }
    }else{
        header("Location: /agendaSenac");
        exit;
    }
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
            $contato->editar($nome, $email, $telefone, $cidade, $rua, $numero, $bairro, $cep, $profissao, $dt_nasc, $foto, $_GET['id']);
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
?>
<h1>EDITAR CONTATO</h1>
<form method="POST" enctype="multipart/form-data"><!--permite adicionar imagens no form-->
    <input type="hidden" name="id" value="<?php echo $info['id']?>">
    Nome: <br>
    <input type="text" name="nome" value="<?php echo $info['nome']?>"/><br><br>
    Email: <br>
    <input type="text" name="email" value="<?php echo $info['email']?>"/><br><br>
    Telefone: <br>
    <input type="text" name="telefone" value="<?php echo $info['telefone']?>"/><br><br>
    Cidade: <br>
    <input type="text" name="cidade" value="<?php echo $info['cidade']?>"/><br><br>
    Rua: <br>
    <input type="text" name="rua" value="<?php echo $info['rua']?>"/><br><br>
    Número: <br>
    <input type="text" name="numero" value="<?php echo $info['numero']?>"/><br><br>
    Bairro: <br>
    <input type="text" name="bairro" value="<?php echo $info['bairro']?>"/><br><br>
    CEP: <br>
    <input type="text" name="cep" value="<?php echo $info['cep']?>"/><br><br>
    Profissão: <br>
    <input type="text" name="profissao" value="<?php echo $info['profissao']?>"/><br><br>
    Nascimento: <br>
    <input type="date" name="dt_nasc" value="<?php echo $info['dt_nasc']?>"/><br><br>
    Foto Contato: <br>
    <input type="file" name="foto[]" multiple/><br>

    <div class="grupo">
        <div class="cabecalho">Foto Contato</div>
        <div class="corpo">
        <?php foreach($info['foto'] as $fotos):?>
            <div class="foto_item">
                <img src="img/contatos/<?php echo $fotos['url'];?>"/>
                <a href="excluir_foto.php?id=<?php $fotos['id']; ?>">Excluir Imagem</a>
            </div>
        <?php endforeach; ?>
        </div>
    </div>

    <input type="submit" name="btCadastrar" value="SALVAR"/>
</form>


   