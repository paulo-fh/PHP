<?php 
    class Cliente{

        private $pdo;

        //6 funções
        //CONSTRUTOR - Conexão com o Banco de dados
        public function __construct($dbname, $host, $user, $senha)
        {
            try {
                $this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host,$user,$senha);
            } catch (PDOException $e) {
                echo "Erro com o banco de dados: ".$e->getMessage();
                exit();
            } catch (Exception $e){
                echo "Erro generico: ".$e->getMessage();
                exit();
            }
        }

        //Função para buscar os dados e imprimir na tabela criada
        public function buscarDados()
        {
            $res = array();
            $cmd = $this->pdo->query("SELECT * FROM clientes ORDER BY nome");
            $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }

        //Função para cadastrar os clientes no banco de dados
        public function cadastrarCliente($nome, $telefone, $email)
        {
            //Antes de cadastrar verificar se ja tem o email
            $cmd = $this->pdo->prepare("SELECT id FROM clientes WHERE email = :e");
            $cmd->bindValue(":e",$email);
            $cmd->execute();
            if ($cmd->rowCount() > 0) //email já existe no banco
            {
                return false;
            }
            else //não foi encontrado o email no banco
            {
                $cmd = $this->pdo->prepare("INSERT INTO clientes (nome, telefone, email) VALUES (:n, :t, :e)");
                $cmd->bindValue(":n", $nome);
                $cmd->bindValue(":t", $telefone);
                $cmd->bindValue(":e", $email);
                $cmd->execute();
                return true;
            }
        }


        //Função para excluir clientes no banco de dados
        public function excluirPessoa($id)
        {
            $cmd = $this->pdo->prepare("DELETE FROM clientes WHERE id = :id");
            $cmd->bindValue(":id",$id);
            $cmd->execute();
        }

        //Função para buscar dados de um cliente
        public function buscarDadosPessoa($id)
        {
            $res = array();
            $cmd = $this->pdo->prepare("SELECT * FROM clientes WHERE id = :id");
            $cmd->bindValue(":id", $id);
            $cmd->execute();
            $res = $cmd->fetch(PDO::FETCH_ASSOC);
            return $res;
        }


        //Atualizar dados do cliente no banco
        public function atualizarDados($id, $nome, $telefone, $email)
        {
           
            $cmd = $this->pdo->prepare("UPDATE clientes SET nome = :n, telefone = :t, email = :e WHERE id = :id");
            $cmd->bindValue(":n", $nome);
            $cmd->bindValue(":t", $telefone);
            $cmd->bindValue(":e", $email);
            $cmd->bindValue(":id", $id);
            $cmd->execute();
        }

           //Função para buscar os dados e imprimir na tabela criada
           public function buscarCampanha()
           {
               $res = array();
               $cmd = $this->pdo->query("SELECT * FROM campanhas ORDER BY nome_campanha");
               $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
               return $res;
           }
             //Função para cadastrar campanhas no banco de dados
            public function cadastrarCampanha($campanha)
            {            
                $cmd = $this->pdo->prepare("INSERT INTO campanhas (nome_campanha) VALUES (:nc)");
                $cmd->bindValue(":nc", $campanha);
                $cmd->execute();
                return true;            
            }

            //Função para buscar os dados e imprimir na tabela criada
           public function qtdCampanha()
           {
               $res = array();
               $cmd = $this->pdo->query("SELECT COUNT(id) FROM clientes");
               $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
               return $res;
           }
        
    }
?>