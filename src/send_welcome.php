<?php

    header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Content-Type: application/json');

    use Mailgun\Mailgun;
    require '../mailgun/vendor/autoload.php';
    require '../sendgrid/vendor/autoload.php';

    $email = $_GET['Email'];
    $nickName = $_GET['UsuarioName'];

    //$welcome = sendWelcomeMailGun($email, $nickName);
    $welcome = sendWelcomeSendGrid($email, $nickName);
 
    function sendWelcomeSendGrid($email, $nickName){
        include('../sendgrid/credentials_sendgrid.php');

        $template_id = 'd-54ce59c7f79a4e3ca1069b6761d5d334';

        $mail = new \SendGrid\Mail\Mail(); 
        $mail->setFrom("noreply@tecnofun.es", "Tecnofun Invoice");
        $mail->addTo($email);
        $mail->setTemplateId(
            new \SendGrid\Mail\TemplateId( $template_id )
        );

        // === Here comes the dynamic template data! ===
        $mail->addDynamicTemplateDatas([
        'varuser' => $nickName
        ]);


        $sendgrid = new \SendGrid($API_KEY);

        try {
            $response = $sendgrid->send($mail);
            echo ' Si no lo recibes, revisa la carpeta de SPAM del correo', '<br><br>', ' Gracias';
        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }
    }


    function sendWelcomeMailGun($email, $nickName){
        include('../mailgun/credentials_mailgun.php');
        $mg = Mailgun::create($API_KEY_MAILGUN, 'https://api.eu.mailgun.net'); // For EU servers
        $domain = 'correo.tecnofun.es';

        $params = array(
            'from'     => 'Tecnofun Invoice <noreply@tecnofun.es>',
            'to'       => $email,
            'subject'  => 'Bienvenido a Tecnofun Invoice',
            'template' => 'welcome1',
            'v:varuser'  => $nickName
        );

        try {
            $response = $mg->messages()->send($domain, $params);
            echo ' Si no lo recibes, revisa la carpeta de SPAM del correo', '<br><br>', ' Gracias';
        } 
        catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }
    
    } 
      
?>