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
    private $dt_nasc;
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
    public function adicionar($email, $nome, $telefone, $cidade, $rua, $numero, $bairro, $cep, $profissao, $dt_nasc){
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
                $this->dt_nasc = implode("-", array_reverse(explode("/",$dt_nasc )));
                $sql = $this->con->conectar()->prepare("INSERT INTO contatos(nome, email, telefone, cidade, rua, numero, bairro, cep, profissao, dt_nasc) VALUES(:nome, :email, :telefone, :cidade, :rua, :numero, :bairro, :cep, :profissao, :dt_nasc)");
                $sql->bindParam(":nome", $this->nome, PDO::PARAM_STR);
                $sql->bindParam(":email", $this->email, PDO::PARAM_STR);
                $sql->bindParam(":telefone", $this->telefone, PDO::PARAM_STR);
                $sql->bindParam(":cidade", $this->cidade, PDO::PARAM_STR);
                $sql->bindParam(":rua", $this->rua, PDO::PARAM_STR);
                $sql->bindParam(":numero", $this->numero, PDO::PARAM_STR);
                $sql->bindParam(":bairro", $this->bairro, PDO::PARAM_STR);
                $sql->bindParam(":cep", $this->cep, PDO::PARAM_STR);
                $sql->bindParam(":profissao", $this->profissao, PDO::PARAM_STR);
                $sql->bindParam(":dt_nasc", $this->dt_nasc, PDO::PARAM_STR);
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
            $sql = $this->con->conectar()->prepare("SELECT id, nome, email, telefone, cidade, rua, numero, bairro, cep, profissao, dt_nasc FROM contatos");
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
    public function getContato($id) {
        $array = array();

        $sql = $this->con->conectar()->prepare("SELECT * FROM contatos WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();

        if($sql-> rowCount() > 0){
            $array = $sql->fetch();
            //mostrar todas as imagens cadastradas
            $array['foto'] = array();
            $sql = $this->con->conectar()->prepare("SELECT id, url FROM contato_foto WHERE id_contato = :id_contato");
            $sql->bindValue("id_contato", $id);
            $sql->execute();

            if($sql->rowCount() > 0){
                $array['foto'] = $sql->fetchAll();
            }
        }
        return $array;
    }
    public function getFoto(){
        $array = array();
        $sql = $this->con->conectar()->prepare("SELECT *, 
        (select contato_foto.url from contato_foto where contato_foto.id_contato = contatos.id limit 1) as url
        FROM contatos");
        $sql->execute();

        if($sql->rowCount() > 0){
            $array = $sql->fetchAll();
        }
        return $array;
    }
    public function editar($nome, $email, $telefone, $cidade, $rua, $numero, $bairro, $cep, $profissao, $dt_nasc, $foto, $id){
        $emailExistente = $this->existeEmail($email);
        if(count($emailExistente) > 0 && $emailExistente['id'] != $id){
            return FALSE;
        }else{
            try{
                $this->dt_nasc = implode("-", array_reverse(explode("/",$dt_nasc )));

                $sql = $this->con->conectar()->prepare("UPDATE contatos SET nome = :nome, email = :email, telefone = :telefone, cidade = :cidade, rua = :rua, numero = :numero, bairro = :bairro, cep = :cep, profissao = :profissao, dt_nasc = :dt_nasc WHERE id = :id");
                $sql->bindValue(':nome', $nome);
                $sql->bindValue(':email', $email);
                $sql->bindValue(':telefone', $telefone);
                $sql->bindValue(':cidade', $cidade);
                $sql->bindValue(':rua', $rua);
                $sql->bindValue(':numero', $numero);
                $sql->bindValue(':bairro', $bairro);
                $sql->bindValue(':cep', $cep);
                $sql->bindValue(':profissao', $profissao);
                $sql->bindValue(':dt_nasc', $dt_nasc);
                $sql->bindValue(':id', $id);
                $sql->execute();
                //inserir a imagem
                if(count($foto) > 0){
                    for($q=0;$q<count($foto['tmp_name']);$q++){
                        $tipo = $foto['type'][$q];
                        if(in_array($tipo, array('image/jpeg', 'image/png'))){
                            $tmpname = md5(time().rand(0, 9999)).'.jpg';
                            move_uploaded_file($foto['tmp_name'][$q], 'img/contatos/'.$tmpname);
                            list($width_orig, $height_orig) = getimagesize('img/contatos/'.$tmpname);
                            $ratio = $width_orig/$height_orig;

                            $width = 500;
                            $height = 500;

                            if($width/$height > $ratio){
                                $width = $height*$ratio;
                            }else{
                                $height = $width/$ratio;
                            }

                            $img = imagecreatetruecolor($width, $height);
                            if($tipo == 'image/jpeg'){
                                $origi = imagecreatefromjpeg('img/contatos/'.$tmpname);
                            }elseif ($tipo == 'image/png'){
                                $origi = imagecreatefrompng('img/contatos/'.$tmpname);
                            }
                            imagecopyresampled($img, $origi, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
                            //imagem salva no servidor
                            imagejpeg($img, 'img/contatos/'.$tmpname, 80);
                            //salvar no banco de dados a url
                            $sql = $this->con->conectar()->prepare("INSERT INTO contato_foto SET id_contato = :id_contato, url = :url");
                            $sql->bindValue(":id_contato", $id);
                            $sql->bindValue(":url", $tmpname);
                            $sql->execute();
                        }
                    }
                }
                return TRUE;
            }catch(PDOException $ex){
                echo 'ERRO: '.$ex->getMessage();
            }
        }
    }
    public function excluir($id){
        $sql = $this->con->conectar()->prepare("DELETE FROM contato_foto WHERE id_contato = :id_contato");
        $sql->bindValue(":id_contato", $id);
        $sql->execute();

        $sql = $this->con->conectar()->prepare("DELETE FROM contatos WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();
    }
    public function excluirFoto($id){
        $id_contato = 0;
        $sql = $this->con->conectar()->prepare("SELECT id_contato FROM contato_foto WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $row = $sql->fetch();
            $id_contato = $row['id_contato'];
        }
        $sql = $this->con->conectar()->prepare("DELETE FROM contato_foto WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();

        return $id_contato;
    }
}
