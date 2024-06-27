<?php
require_once 'conexao.class.php';

class Usuarios {
    private $con;

    private $id;
    private $nome;
    private $email;
    private $senha;
    private $permissoes;

    public function __construct(){
        $this->con = new Conexao();
    }
    public function __set($atributo, $valor){
        $this->atributo = $valor;
    }
    public function __get($atributo){
        return $this->atributo;
    }
   //Aqui ficará o CRUD de usuários
   
   //fim CRUD usuários.
   public function fazerLogin($email, $senha){
        $sql = $this->con->conectar()->prepare("SELECT * FROM usuarios WHERE email = :email AND senha = :senha");
        $sql->bindValue(":email", $email);
        $sql->bindValue(":senha", $senha);
        $sql->execute();

        if($sql->rowCount() > 0){
            $sql = $sql->fetch();

            $_SESSION['logado'] = $sql['id'];
            return TRUE;
        }
        return FALSE;
   }
   public function alterarSenha($email, $senha, $id){
        
   }
   public function setUsuario($id){
    $this->id = $id;
    $sql = $this->con->conectar()->prepare("SELECT * FROM usuarios WHERE id = :id");
    $sql->bindValue(':id', $id);
    $sql->execute();
    
    if($sql->rowCount() > 0){
        $sql = $sql->fetch();
        $this->permissoes = explode(',', $sql['permissoes']); //transforma em array  (add,edit,del,super)
    }
   }
   public function getPermissoes(){
    return $this->permissoes;
   }
   public function temPermissoes($p){
    if(in_array($p, $this->permissoes)){
        return TRUE;
    }else{
        return FALSE;
    }
   }
}