<?php 
    session_start();
    if(!isset($_SESSION['id_usuario']))
    {
        header("location: index.php");
        exit;
    }

    require_once 'classes/cliente.php';
    $clien = new Cliente("projeto_oslec","localhost","root","");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de menu</title>
    <link rel="stylesheet" href="css/menu.css">
</head>
<body id="tela-menu">
    
    <header id="menu">        
        <a href="logout.php">Logout</a>
        <h1>OSLEC Tecnology</h1>
        <p>Marketing Digital</p>
        <a href="send.php" id="email">Enviar e-mail</a>
    </header>
    
    <main>
        <?php 
            if(isset($_GET['id_up'])) //verificar se clicou em editar
            {
                $id_update = addslashes($_GET['id_up']);
                $res = $clien->buscarDadosPessoa($id_update);
            }
        
        ?>
        <section>
            <form method="post">
                <h2>CADASTRAR CLIENTES</h2>
                <label for="nome">Nome</label>
                <input name="nome" type="text" id="nome" value="<?php if(isset($res)){echo $res['nome'];} ?>">
                <label for="telefone">Telefone</label>
                <input name="telefone" type="text" id="telefone" value="<?php if(isset($res)){echo $res['telefone'];} ?>">
                <label for="email">Email</label>
                <input name="email" type="email" id="email" value="<?php if(isset($res)){echo $res['email'];} ?>">
                <input type="submit" value="<?php if(isset($res)){echo "Atualizar";}else{echo "Cadastrar";}?>">
            </form>
            <?php 
                if(isset($_POST['nome'])) //clicou no botão cadastrar ou editar
                {
                    //botão editar
                    if(isset($_GET['id_up']) && !empty($_GET['id_up']))
                    {
                        $id_upd = addslashes($_GET['id_up']);
                        $nome = addslashes($_POST['nome']);
                        $telefone = addslashes($_POST['telefone']);
                        $email = addslashes($_POST['email']);
                        if(!empty($nome) && !empty($telefone) && !empty($email))
                        {
                            //Editar
                            $clien->atualizarDados($id_upd, $nome, $telefone, $email);   
                            header("location: menu.php");                 
                        }
                        else
                        {
            ?>
                            <div class="aviso">
                                <h4>Preencha todos os campos!</h4>
                            </div>
            <?php 
                        }

                    }
                    else // Cadastrar
                    {
                        $nome = addslashes($_POST['nome']);
                        $telefone = addslashes($_POST['telefone']);
                        $email = addslashes($_POST['email']);
                        if(!empty($nome) && !empty($telefone) && !empty($email))
                        {
                            //cadastrar
                            if(!$clien->cadastrarCliente($nome, $telefone, $email))
                            {
            ?>
                            <div class="aviso">
                                <h4>Email já está cadastrado</h4>
                            </div>
            <?php 
                                
                            }
                        }
                        else
                        {
            ?>
                            <div class="aviso">
                                <h4>Preencha todos os campos!</h4>
                            </div>
            <?php 
                            
                        }
                    }

                }
            
            ?>
        </section>
      
        <section id="tabela">
            <table>
                <h2 id="tabela">CLIENTES CADASTRADOS</h2>
                <tr id="titulo">
                    <td>NOME</td>
                    <td>TELEFONE</td>
                    <td colspan="2">EMAIL</td>
                </tr>
                <?php 
                    $dados = $clien->buscarDados();
                    if(count($dados) > 0) //Tem pessoas cadastradas no banco
                    {
                        for ($i=0; $i < count($dados); $i++) 
                        { 
                            echo "<tr>";
                            foreach($dados[$i] as $k => $v)
                            {
                                if($k != "id")
                                {
                                    echo "<td>$v</td>";
                                }
                            }   
                ?>
                    <td>
                        <a href="menu.php?id_up=<?= $dados[$i]['id'] ?>">Editar</a>
                        <a href="menu.php?id=<?= $dados[$i]['id'] ?>">Excluir</a>
                    </td>
                <?php
                            echo "</tr>"; 
                                   
                        }                                             
                    }
                    else  //O banco está vazio
                    {
                ?>
                
            </table>
                        <div class="aviso">
                            <h4>Ainda não há pessoas cadastradas!</h4>
                        </div>
                    <?php 
                    }
                    ?>
         
        </section>
    </main>
 
</body>
</html>

<?php 
    if(isset($_GET['id']))
    {
        $id_pessoa = addslashes($_GET['id']);
        $clien->excluirPessoa($id_pessoa);
        header("location: menu.php");
    }
?>