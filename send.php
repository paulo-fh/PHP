<?php 
    session_start();

    require_once 'classes/cliente.php';
    $clien = new Cliente("projeto_oslec","localhost","root","");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envio de Email</title>
    <link rel="stylesheet" href="css/menu.css">
</head>
<body id="tela-menu">

    <?php 
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';

        if(isset($_POST["send"])){
            $mail = new PHPMailer(true);

            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'oslectecnology@gmail.com';             //seu gmail
            $mail->Password   = 'wrnptaqxnookfadf';                     //sua senha app do email
            $mail->SMTPSecure = 'ssl';                                  //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set 

            $mail->setFrom('oslectecnology@gmail.com');  //seu gmail
          
            for ($i=0; $i < count($_FILES['file']['tmp_name']) ; $i++) { 
                $mail->addAttachment($_FILES['file']['tmp_name'][$i], $_FILES['file']['name'][$i]);   //Optional name
            }

            $mail->isHTML(true);   //Set email format to HTML

            $mail->Subject = $_POST['subject'];

            $mail->Body    = $_POST['message'];
            
            $dados = $clien->buscarDados();

            $titulo = addslashes($_POST['subject']);
            $clien->cadastrarCampanha($titulo);
            
            for($z=0, $qtdEmail=1; $z<count($dados); $z++){
                
                $mail->addAddress($dados[$z]['email']);   //Add a recipient
                $qtdEmail += $z;

                if($mail->send()){
                    $message = "<div class='sucesso'>E-mail enviado com sucesso</div> <br>";
                    if($z % 50 == 0) {
                        sleep(15);
                    }
                }else{
                    echo "Falha ao enviar e-mail para $dados <br>";
                }               
                
                $mail->ClearAllRecipients();
            }

        }
    ?>

    <header style="padding-bottom: 37px;" id="menu">        
        <a href="menu.php">Voltar</a>
        <h1>OSLEC Tecnology</h1>
        <p>Marketing Digital</p>
    </header>

    <main>
        <section>
            <div class="form-envio">
                <form id="envio" method="post" name="emailContact" enctype="multipart/form-data">
                    <h2>ENVIO DE CAMPANHAS</h2>
                    <div class="input-envio">
                        <label>Titulo da Campanha<em>*</em></label>
                        <input type="text" name="subject" required>
                    </div>
                    <div class="input-envio">
                        <label>Menssagem <em>*</em></label>
                        <textarea name="message" required></textarea>
                    </div>
                    <div>
                        <input type="file" multiple='multiple' name="file[]">
                    </div>
                    <div class="input-envio">
                        <input type="submit" name="send" value="Enviar">
                        <?php if(!empty($message)){ ?>
                        <div class="success">
                            <strong><?php echo $message; ?></strong>
                        </div>
                        <?php }?>
                    </div>
                </form>                
            </div>
        </section>

        <section id="tabela">
            <h2 id="tabela">ESTATÍTICA DE ENVIOS</h2>
            <table>
                <tr id="titulo">
                    <td >CAMPANHA</td>
                    <td >Qtd de envio</td>
                </tr>
                <?php 
                    $dados = $clien->buscarCampanha();
                    $dados2 = $clien->buscarDados();
                    $total = count($dados2);                

                    if(count($dados) > 0) //Tem pessoas cadastradas no banco
                    {
                        for ($i=0; $i < count($dados); $i++) 
                        { 
                            echo "<tr>";
                            foreach($dados[$i] as $k => $v)
                            {
                                if($k != "id_usuario")
                                {
                                    echo "<td>$v</td>";   
                                    echo "<td>$total</td>";                             
                                }
                            }   
                        }                        
                    }
                    ?>
                
            </table>
                                
        </section>
    </main>
</body>
</html>

