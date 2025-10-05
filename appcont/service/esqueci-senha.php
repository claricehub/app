<?php
session_start();


require_once '../db/db.php';




try {
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'claricexl_@hotmail.com';
    $mail->Password = 'ihdzmdbxvjtegfgi'; // senha de app
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('claricexl_@hotmail.com', 'Clarice Teste');
    $mail->addAddress('veigaclarice04@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'Teste de envio';
    $mail->Body = 'Este é um teste de envio com Outlook e PHPMailer.';

    $mail->send();
    echo '✅ E-mail enviado com sucesso!';
} catch (Exception $e) {
    echo "❌ Erro ao enviar: {$mail->ErrorInfo}";
}

?>
