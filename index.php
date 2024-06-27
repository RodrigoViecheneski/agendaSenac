<?php
session_start();
include 'classes/contatos.class.php';
include 'classes/usuarios.class.php';
if(!isset($_SESSION['logado'])){
    header("Location: login.php");
    exit;
}
$usuarios = new Usuarios();
$usuarios->setUsuario($_SESSION['logado']);
$contato = new Contatos();
?>
<h1>Agenda Senac</h1>
<hr>
<?php if($usuarios->temPermissoes('add')):  ?><button><a href="adicionarContato.php">ADICIONAR</a></button><?php endif; ?><br><br>
<button><a href="sair.php">SAIR</a></button>
<br><br>
<table border="2" width=100%>
    <tr>
        <th>ID</th>
        <th>NOME</th>
        <th>EMAIL</th>
        <th>TELEFONE</th>
        <th>CIDADE</th>
        <th>RUA</th>
        <th>NÚMERO</th>
        <th>BAIRRO</th>
        <th>CEP</th>
        <th>PROFISSÃO</th>
        <th>Nascimento</th>
        <th>FOTO</th>
        <th>AÇÕES</th>
    </tr>
   <?php
   $lista = $contato->getFoto();
   foreach($lista as $item) :
   ?>
    <tbody>
        <tr>
            <td><?php echo $item['id'];?></td>
            <td><?php echo $item['nome'];?></td>
            <td><?php echo $item['email'];?></td>
            <td><?php echo $item['telefone'];?></td>
            <td><?php echo $item['cidade'];?></td>
            <td><?php echo $item['rua'];?></td>
            <td><?php echo $item['numero'];?></td>
            <td><?php echo $item['bairro'];?></td>
            <td><?php echo $item['cep'];?></td>
            <td><?php echo $item['profissao'];?></td>
            <td><?php echo implode("/",array_reverse(explode("-", $item['dt_nasc'])));?></td>
           <?php
               // $contatos = $contato->getFoto();
               // foreach($contatos as $foto):
           ?>
            <td>
                <?php if(!empty($item['url'])): ?>
                    <img src="img/contatos/<?php echo $item['url']; ?>" height="50px" border="0"/>
                <?php else: ?>
                    <img src="img/default.png" height="50px" border="0" />
                <?php endif; ?>
                <?php //endforeach; ?>
            </td>
            <td>
                <?php if($usuarios->temPermissoes('edit')): ?><a href="editarContato.php?id=<?php echo $item['id'];?>">EDITAR</a><?php endif; ?>
                <?php if($usuarios->temPermissoes('del')): ?><a href="excluirContato.php?id=<?php echo $item['id'];?>" onclick="return confirm('Tem certeza que quer excluir este contato?')">| EXCLUIR</a><?php endif; ?>
            </td>
        </tr>
    </tbody>
    <?php
    endforeach;
    ?>
</table>
