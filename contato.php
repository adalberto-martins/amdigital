<?php

// Importa as classes do PHPMailer no namespace global
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// -----------------------------------------------------------------------------------
// IMPORTANTE:
// 1. Você deve baixar o PHPMailer (biblioteca gratuita)
// 2. Colocar os arquivos .php nas pastas abaixo no seu servidor.
// 3. Altere o 'caminho/para/' para o local correto onde você fez o upload.
// -----------------------------------------------------------------------------------


require __DIR__ .'PHPMailer/Exception.php';
require __DIR__.'PHPMailer/PHPMailer.php';
require __DIR__. 'PHPMailer/SMTP.php';


// Verifica se a requisição foi feita usando o método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Coleta e sanitiza os dados do formulário
    $nome = htmlspecialchars(trim($_POST['nome']));
    $telefone = htmlspecialchars(trim($_POST['tel']));
    $email = htmlspecialchars(trim($_POST['email']));
    $assunto = htmlspecialchars(trim($_POST['ass']));

    // Valida os dados para garantir que não estão vazios
    if (empty($nome) || empty($email) || empty($assunto)) {
        // Redireciona de volta ao formulário com uma mensagem de erro
        header('Location: index.html?status=erro');
        exit;
    }

    // Configurações de destino
    // **Substitua pelo seu endereço de e-mail REAL**
    $para = "adalberto_martins@outlook.com.br"; 
    $titulo_email = "Novo Contato do Site AM Digital: " . $nome;

    // Conteúdo do e-mail em HTML
    $corpo_email = "
    <html>
    <head>
        <title>Novo Contato do Site AM Digital</title>
    </head>
    <body>
        <h2>Informações do Contato</h2>
        <p><strong>Nome:</strong> {$nome}</p>
        <p><strong>Telefone:</strong> {$telefone}</p>
        <p><strong>E-mail:</strong> {$email}</p>
        <p><strong>Assunto:</strong> {$assunto}</p>
    </body>
    </html>
    ";

    $mail = new PHPMailer(true); // Cria uma nova instância, 'true' habilita exceções

    try {
        // -----------------------------------------------------------------
        // 1. CONFIGURAÇÕES SMTP (Altere estas linhas!)
        // -----------------------------------------------------------------
        $mail->isSMTP(); 
        // Servidor SMTP. EX: smtp.office365.com (Outlook/Hotmail) ou smtp.gmail.com
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true; 
        // Seu email (deve ser o mesmo configurado no Host)
        $mail->Username   = 'amdscff@gmail.com';
        // Sua senha ou Senha de Aplicativo (Recomendado para Gmail/Outlook)
        $mail->Password   = 'Nd6gcsf4#'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Corrigido!
        $mail->Port       = 587; // Porta segura padrão
        
        // Configurações de Charset
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        // -----------------------------------------------------------------
        // 2. DESTINATÁRIOS
        // -----------------------------------------------------------------
        // Remetente (O email que vai aparecer no "De:"). 
        // É recomendável usar seu e-mail SMTP, não o e-mail do formulário.
        $mail->setFrom('seu_email_para_autenticacao@exemplo.com', 'Formulário AM Digital');
        // Define o endereço de resposta para o e-mail do usuário do formulário
        $mail->addReplyTo($email, $nome); 
        // Adiciona o seu e-mail de destino
        $mail->addAddress($para, 'Destinatário AM Digital'); 

        // -----------------------------------------------------------------
        // 3. CONTEÚDO
        // -----------------------------------------------------------------
        $mail->isHTML(true); 
        $mail->Subject = $titulo_email;
        $mail->Body    = $corpo_email;
        // Versão texto simples (para clientes de email que não suportam HTML)
        $mail->AltBody = "Novo contato. Nome: {$nome}, E-mail: {$email}, Telefone: {$telefone}, Assunto: {$assunto}."; 

        $mail->send();
        
        // Redireciona para sucesso
        header('Location: index.html?status=sucesso');
        exit;

    } catch (Exception $e) {
        // Redireciona para erro
        // Para debug, substitua o 'header()' por: echo "Erro do Mailer: {$mail->ErrorInfo}";
        header('Location: index.html?status=erro_envio');
        exit;
    }
} else {
    // Se a requisição não for POST, redireciona para a página inicial
    header('Location: index.html');
    exit;
}

?>