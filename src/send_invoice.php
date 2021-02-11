<?php 
    header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Content-Type: application/json');

    //necesario para enviar con mailgun    
    use Mailgun\Mailgun;
    require '../mailgun/vendor/autoload.php';
    //necesario para enviar con sendgrid
    require '../sendgrid/vendor/autoload.php';
    $OrganizationName = $_GET['OrganizationName'];
    $CustomerName = $_GET['CustomerName'];
    $Email = $_GET['Email'];
    $InvoiceNumber = $_GET['InvoiceNumber'];
    $FileName = $_GET['FileName'];
    $urlpdf = $_GET['urlpdf'];

    if($Email <> ""){
        $send = sendgridTemplate_API($OrganizationName, $CustomerName, $Email, $InvoiceNumber, $FileName, $urlpdf);
        //$send = mailgunTemplate_API($OrganizationName, $CustomerName, $Email, $InvoiceNumber, $FileName, $urlpdf);
    } else {
		echo 'No existe este email ';
    }

    function sendgridTemplate_API($OrganizationName, $CustomerName, $Email, $InvoiceNumber, $FileName, $urlpdf){
        include('../sendgrid/credentials_sendgrid.php');
        $template_id = 'd-366c16def23f4f8da0d85e83779f492f';

        $mail = new \SendGrid\Mail\Mail(); 
        $mail->setFrom("noreply@tecnofun.es", "Tecnofun Invoice");
        $mail->addTo($Email);
        $mail->setTemplateId(
            new \SendGrid\Mail\TemplateId( $template_id )
        );
        
        // === Here comes the dynamic template data! ===
        $mail->addDynamicTemplateDatas( [
            'vartitle'     => 'Descargar aquí',
            'varinvoicenumber' => $InvoiceNumber,
            'varorganizationname' => $OrganizationName,
            'varcustomername' => $CustomerName,
            'varurl' => $urlpdf
        ] );

        $file_encoded = base64_encode(file_get_contents($FileName));
        //$file_encoded = file_get_contents($FileName);
        $mail->addAttachment(
            $file_encoded,
            "application/pdf",
            "Factura_".$InvoiceNumber.".pdf",
            "attachment"
        );

        $sendgrid = new \SendGrid($API_KEY);

        try {
            $response = $sendgrid->send($mail);
            echo ' Si no lo recibes, revisa la carpeta de SPAM del correo', '<br><br>', ' Gracias';
            //print $response->statusCode() . "\n";
            //print_r($response->headers());
            //print $response->body() . "\n";
        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }
    }


    //envío de email utilizando la API de mailgun y template
   function mailgunTemplate_API($OrganizationName, $CustomerName, $Email, $InvoiceNumber, $FileName, $urlpdf){
        include('../mailgun/credentials_mailgun.php');
        $mg = Mailgun::create($API_KEY_MAILGUN, 'https://api.eu.mailgun.net'); // For EU servers

        $domain = 'correo.tecnofun.es';
        $vartitle = 'FACTURA';
        
        //ejemplo para enviar adjunto
    
        $file = array(
                    array(
                        'filePath' => $FileName,
                        'filename' => 'Factura_'.$InvoiceNumber.'.pdf'
                )
            );
    
        $params = array(
            'from'     => 'Tecnofun Invoice <noreply@tecnofun.es>',
            'to'       => $Email,
            'subject'  => 'Invoice - '.$InvoiceNumber.' From '.$OrganizationName,
            'template' => 'invoice-pdf',
            'v:vartitle'  => $vartitle,
            'v:varcustomername' => $CustomerName,
            'v:varinvoicenumber' => $InvoiceNumber,
            'v:varurl'   => $urlpdf,
            'attachment' => $file
        );
        # Make the call to the client.
        //$result = $mg->messages()->send($domain, $params);
        try {
            $response = $mg->messages()->send($domain, $params);
            echo ' Si no lo recibes, revisa la carpeta de SPAM del correo', '<br><br>', ' Gracias';
            //print $response->statusCode() . "\n";
            //print_r($response->headers());
            //print $response->body() . "\n";
        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }
    }
?>