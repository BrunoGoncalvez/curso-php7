<?php

    class Usuario{

        private $id_usuario;
        private $des_login;
        private $des_senha;
        private $dt_cadastro;


        public function getIdusuario(){
            return $this->id_usuario;
        }

        public function setIdusuario($value){
            $this->id_usuario = $value;
        }


        public function getDeslogin(){
            return $this->des_login;
        }

        public function setDeslogin($value){
            $this->des_login = $value;
        }


        public function getDessenha(){
            return $this->des_senha;
        }


        public function setDessenha($value){
            $this->des_senha = $value;
        }


        public function getDtcadastro(){
            return $this->dt_cadastro;
        }

        public function setDtcadastro($value){
            $this->dt_cadastro = $value;
        }

        public function loadById($id){

            $banco = new Banco();

            $results = $banco->select("SELECT * FROM tb_usuarios WHERE id_usuario = :ID", array(

                ":ID"=>$id 

            ));

            // if(count($results) > 0){

            // }  Outra forma de se fazer

            if(isset($results[0])){

                $row = $results[0];

                $this->setData($results[0]);

            }
        }

        public static function getList(){

            $banco = new Banco();

            return $banco->select("SELECT * FROM tb_usuarios ORDER BY des_login");

        }

        public static function search($login){

            $banco = new Banco();

            return $banco->select("SELECT * FROM tb_usuarios WHERE des_login LIKE :SEARCH ORDER BY des_login", array(
                ':SEARCH'=>"%".$login."%"
            ));

        }

        public function login($login, $password){

            $banco = new Banco();

            $results = $banco->select("SELECT * FROM tb_usuarios WHERE des_login = :LOGIN AND des_senha = :PASSWORD", array(
                ":LOGIN"=>$login,
                ":PASSWORD"=>$password
            ));

            if(count($results) > 0){

                $row = $results[0];

                $this->setData($results[0]);

            }else{
                throw new Exception("Login e/ou Senha inv??lidos.");
            }

        }

        public function setData($data){

            $this->setIdusuario($data['id_usuario']);
            $this->setDeslogin($data['des_login']);
            $this->setDessenha($data['des_senha']);
            $this->setDtcadastro(new DateTime($data['dt_cadastro']));

        }

        public function insert(){

            $banco = new Banco();

            $results = $banco->select("CALL sp_usuarios_insert(:LOGIN, :PASSWORD)", array(
                ':LOGIN'=>$this->getDeslogin(),
                ':PASSWORD'=>$this->getDessenha()
            ));

            if(count($results) > 0){

                $this->setData($results[0]);

            }

        }

        public function update($login, $password){

            $this->setDeslogin($login);
            $this->setDessenha($password);

            $banco = new Banco();
            $banco->query("UPDATE tb_usuarios SET des_login = :LOGIN, des_senha = :PASSWORD WHERE id_usuario = :ID ", array(

                ':LOGIN'=>$this->getDeslogin(),
                ':PASSWORD'=>$this->getDessenha(),
                ':ID'=>$this->getIdusuario()
            ));
        }

        public function delete(){

            $banco = new Banco();
            $banco->query("DELETE FROM tb_usuarios WHERE id_usuario = :ID", array(
                ':ID'=>$this->getIdusuario()
            ));

            $this->setIdusuario(0);
            $this->setDeslogin("");
            $this->setDessenha("");
            $this->setDtcadastro(new DateTime());
            
        }

        public function __construct($login = "", $password = ""){

            $this->setDeslogin($login);
            $this->setDessenha($password);

        }

        public function __toString(){
            return json_encode(array(
                "id_usuario"=>$this->getIdusuario(),
                "des_login"=>$this->getDeslogin(),
                "des_senha"=>$this->getDessenha(),
                "dt_cadastro"=>$this->getDtcadastro()->format("d/m/Y H:i:s")
            ));
        }

    }

?>