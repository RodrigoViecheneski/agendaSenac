<?php
require 'conexao.class.php';
class Contatos {
    private $id;
    private $nome;
    private $email;
    private $telefone;
    private $cidade;
    private $rua;
    private $numero;
    private $bairro;
    private $cep;
    private $profissao;
    private $foto;

    private $con;

    public function __construct() {
        $this->con = new Conexao();
    }
    private function existeEmail($email){
        $sql = $this->con->conectar()->prepare("SELECT id FROM contatos WHERE email = :email");
        $sql->bindParam(':email', $email, PDO::PARAM_STR);
        $sql->execute();

        if($sql->rowCount() > 0){
            $array = $sql->fetch(); //fetch retorna o valor do b
        }else{
            $array = array();
        }
        return $array;
    }
    public function adicionar($email, $nome, $telefone, $cidade, $rua, $numero, $bairro, $cep, $profissao, $foto){
        $emailExistente = $this->existeEmail($email);
        if(count($emailExistente) == 0){
            try{
                $this->nome = $nome;
                $this->email = $email;
                $this->telefone = $telefone;
                $this->cidade = $cidade;
                $this->rua = $rua;
                $this->numero = $numero;
                $this->bairro = $bairro;
                $this->cep = $cep;
                $this->profissao = $profissao;
                $this->foto = $foto;
                $sql = $this->con->conectar()->prepare("INSERT INTO contatos(nome, email, telefone, cidade, rua, numero, bairro, cep, profissao, foto) VALUES(:nome, :email, :telefone, :cidade, :rua, :numero, :bairro, :cep, :profissao, :foto)");
                $sql->bindParam(":nome", $this->nome, PDO::PARAM_STR);
                $sql->bindParam(":email", $this->email, PDO::PARAM_STR);
                $sql->bindParam(":telefone", $this->telefone, PDO::PARAM_STR);
                $sql->bindParam(":cidade", $this->cidade, PDO::PARAM_STR);
                $sql->bindParam(":rua", $this->rua, PDO::PARAM_STR);
                $sql->bindParam(":numero", $this->numero, PDO::PARAM_STR);
                $sql->bindParam(":bairro", $this->bairro, PDO::PARAM_STR);
                $sql->bindParam(":cep", $this->cep, PDO::PARAM_STR);
                $sql->bindParam(":profissao", $this->profissao, PDO::PARAM_STR);
                $sql->bindParam(":foto", $this->foto, PDO::PARAM_STR);
                $sql->execute();
                return TRUE;
            }catch(PDOException $ex){
                return 'ERRO: '.$ex->getMessage();
            }
            
        }else{
            return FALSE;
        }
    }
    public function listar(){
        try {
            $sql = $this->con->conectar()->prepare("SELECT id, nome, email, telefone, cidade, rua, numero, bairro, cep, profissao, foto FROM contatos");
            $sql->execute();
            return $sql->fetchAll();
        }catch(PDOException $ex){
            return 'ERRO: '.$ex->getMessage();
        }
    }
    public function buscar($id){
        try{
            $sql = $this->con->conectar()->prepare("SELECT * FROM contatos WHERE id = :id");
            $sql->bindValue(':id', $id);
            $sql->execute();
            if($sql->rowCount() > 0){
                return $sql->fetch();
            }else{
                return array();
            }
        }catch(PDOException $ex){
            echo "ERRO: ".$ex->getMessage();
        }
    }
    public function editar($nome, $email, $telefone, $cidade, $rua, $numero, $bairro, $cep, $profissao, $foto, $id){
        $emailExistente = $this->existeEmail($email);
        if(count($emailExistente) > 0 && $emailExistente['id'] != $id){
            return FALSE;
        }else{
            try{
                $sql = $this->con->conectar()->prepare("UPDATE contatos SET nome = :nome, email = :email, telefone = :telefone, cidade = :cidade, rua = :rua, numero = :numero, bairro = :bairro, cep = :cep, profissao = :profissao, foto = :foto WHERE id = :id");
                $sql->bindValue(':nome', $nome);
                $sql->bindValue(':email', $email);
                $sql->bindValue(':telefone', $telefone);
                $sql->bindValue(':cidade', $cidade);
                $sql->bindValue(':rua', $rua);
                $sql->bindValue(':numero', $numero);
                $sql->bindValue(':bairro', $bairro);
                $sql->bindValue(':cep', $cep);
                $sql->bindValue(':profissao', $profissao);
                $sql->bindValue(':foto', $foto);
                $sql->bindValue(':id', $id);
                $sql->execute();
                return TRUE;
            }catch(PDOException $ex){
                echo 'ERRO: '.$ex->getMessage();
            }
        }
    }
    public function excluir($id){
        $sql = $this->con->conectar()->prepare("DELETE FROM contatos WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();
    }
}
