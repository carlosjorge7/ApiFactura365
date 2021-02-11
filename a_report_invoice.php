<?php
    header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Content-Type: application/json');

    require_once "./blobStorage/vendor/autoload.php";

    use MicrosoftAzure\Storage\Blob\BlobRestProxy;
    use MicrosoftAzure\Storage\Blob\BlobSharedAccessSignatureHelper;
    use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
    use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
    use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
    use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
    use MicrosoftAzure\Storage\Blob\Models\DeleteBlobOptions;
    use MicrosoftAzure\Storage\Blob\Models\CreateBlobOptions;
    use MicrosoftAzure\Storage\Blob\Models\GetBlobOptions;
    use MicrosoftAzure\Storage\Blob\Models\ContainerACL;
    use MicrosoftAzure\Storage\Blob\Models\SetBlobPropertiesOptions;
    use MicrosoftAzure\Storage\Blob\Models\ListPageBlobRangesOptions;
    use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
    use MicrosoftAzure\Storage\Common\Exceptions\InvalidArgumentTypeException;
    use MicrosoftAzure\Storage\Common\Internal\Resources;
    use MicrosoftAzure\Storage\Common\Internal\StorageServiceSettings;
    use MicrosoftAzure\Storage\Common\Models\Range;
    use MicrosoftAzure\Storage\Common\Models\Logging;
    use MicrosoftAzure\Storage\Common\Models\Metrics;
    use MicrosoftAzure\Storage\Common\Models\RetentionPolicy;
    use MicrosoftAzure\Storage\Common\Models\ServiceProperties;
    

    $connectionString = 'DefaultEndpointsProtocol=https;AccountName=almacentecnofun;AccountKey=F0Ts3DUzvGj1UKxyuZULgwHRVTe+fFDN+NDpoHBmI7ZpixCKmHfsLdohLrH+FrHDgh0V+3BFHVX7FZc7xdFCrg==';
    $blobClient = BlobRestProxy::createBlobService($connectionString);

    ///////////////////////////////////////////

    // Library: http://www.fpdf.org/
    require './fpdf/fpdf.php';
    // Conexion
    include '../../../deployments/connect/connection_invoice.php'; 
    
 
    class PDF extends FPDF{
        // Cabecera de página
        function Header(){
            // Logo
            //$this->Image('logo.png',10,8,33);
            // Arial bold 15
            $this->SetFont('Arial','B',15);
            // Movernos a la derecha
            $this->Cell(80);
            // Título
            $this->Cell(0,10,'Factura',0,0,'L');
            // Salto de línea
            $this->Ln(20);
        }

        // Pie de página
        function Footer(){
            // Posición: a 1,5 cm del final
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial','I',8);
            // Número de página
            $this->Cell(0,10,utf8_decode('Página '.$this->PageNo()).'/{nb}',0,0,'C');
        }
    }

    $resultadoCabecera = sqlsrv_query($conn, "SELECT o.OrganizationID,
                                             o.OrganizationName,
                                             i.InvoiceID,
                                           	CONVERT(VARCHAR(10), i.InvoiceDate, 103) As InvoiceDate,
                                            i.InvoiceNumber,
                                            CONVERT(VARCHAR(10), i.DueDate, 103) As DueDate,
                                            i.PurchaseOrder,
                                            c.CustomerName,
                                            c.Email,
                                            c.Phone,
                                            c.BillingAddress,
                                            c.BillingStreet2,
                                            c.BillingCity,
                                            c.ShippingAddress,
                                            c.ShippingStreet2, 
                                            c.ShippingCity
                                        FROM 
                                            INVOICE i, ORGANIZATION o, CUSTOMER c
                                        WHERE
                                            i.OrganizationID = o.OrganizationID AND i.CustomerID = c.CustomerID
                                        AND 
                                            InvoiceID = $_GET[InvoiceID]");

    $bodyInvoiceLines = sqlsrv_query($conn, "SELECT ProductName,
                                                ProductDescription,
                                                Quantity,
                                                ProductPrice,
                                                Discount,
                                                ProductTax1Rate,
                                                ProductTax2Rate,
                                                ProductTotal
                                                FROM
                                                INVOICE_LINE
                                                WHERE
                                                InvoiceID = $_GET[InvoiceID]");

    $footerInvoice = sqlsrv_query($conn, "SELECT SubTotal, 
                                            Total, 
                                            Notes,
                                            TermsConditions 
                                            FROM 
                                            INVOICE 
                                            WHERE 
                                            InvoiceID = $_GET[InvoiceID]");

    // Creación del objeto de la clase heredada
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Times','',12);

    $InvoiceID;
    $OrganizationID;
    
    // Cabecera
    while($row = sqlsrv_fetch_array($resultadoCabecera, SQLSRV_FETCH_ASSOC) ) {
        //$pdf->Cell(0, 10, utf8_decode('Organización '. $row['OrganizationID']), 0, 1);
        $OrganizationID = $row['OrganizationID'];
        $pdf->Cell(0, 10, utf8_decode('Organización '. $row['OrganizationName']), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('Fecha '. $row['InvoiceDate']), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('Vencimiento '. $row['DueDate']), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('P.o '. $row['PurchaseOrder']), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('P.o '. $row['InvoiceNumber']), 0, 1);

        //$pdf->Cell(0, 10, utf8_decode('InvoiceID '. $row['InvoiceID']), 0, 1);
        $InvoiceID = $row['InvoiceID'];

        $pdf->Cell(0, 10, utf8_decode('Cliente '. $row['CustomerName']), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('Email '. $row['Email']), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('Teléfono '. $row['Phone']), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('Dirección de envío '. $row['BillingAddress']), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('Dirección de envío 2 '. $row['BillingStreet2']), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('Ciudad '. $row['BillingCity']), 0, 1);

        $pdf->Cell(0, 10, utf8_decode('Dirección de facturación '. $row['ShippingAddress']), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('Dirección de facturación 2 '. $row['ShippingStreet2']), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('Ciudad de facturación '. $row['ShippingCity']), 0, 1);
    }

    $pdf->SetFont('Times','B', 12);

    $pdf->Cell(70, 10, utf8_decode('Producto / Servicio'), 1, 0, 'C', 0);
    //$pdf->Cell(50, 10, utf8_decode('Descripción'), 1, 0, 'C', 0);
    $pdf->Cell(23, 10, utf8_decode('Cantidad'), 1, 0, 'C', 0);
    $pdf->Cell(23, 10, utf8_decode('Precio'), 1, 0, 'C', 0);
    $pdf->Cell(23, 10, utf8_decode('% Dto.'), 1, 0, 'C', 0);
    $pdf->Cell(23, 10, utf8_decode('% Iva'), 1, 0, 'C', 0);
    //$pdf->Cell(25, 10, utf8_decode($row1['ProductTax2Rate']), 1, 0, 'C', 0);
    $pdf->Cell(23, 10, utf8_decode('Importe'), 1, 1, 'C', 0);

    $pdf->SetFont('Times','',12);

    // Lineas
    while($row1 = sqlsrv_fetch_array($bodyInvoiceLines, SQLSRV_FETCH_ASSOC) ) {
        $pdf->Cell(70, 10, utf8_decode($row1['ProductName']), 1, 0, 'C', 0);
        //$pdf->Cell(50, 10, utf8_decode($row1['ProductDescription']), 1, 0, 'C', 0);
        $pdf->Cell(23, 10, utf8_decode($row1['Quantity']), 1, 0, 'C', 0);
        $pdf->Cell(23, 10, utf8_decode($row1['ProductPrice']), 1, 0, 'C', 0);
        $pdf->Cell(23, 10, utf8_decode($row1['Discount']), 1, 0, 'C', 0);
        $pdf->Cell(23, 10, utf8_decode($row1['ProductTax1Rate']), 1, 0, 'C', 0);
        //$pdf->Cell(25, 10, utf8_decode($row1['ProductTax2Rate']), 1, 0, 'C', 0);
        $pdf->Cell(23, 10, utf8_decode($row1['ProductTotal']), 1, 1, 'C', 0);
    }

    // Pie
    while($row2 = sqlsrv_fetch_array($footerInvoice, SQLSRV_FETCH_ASSOC) ) {
        $pdf->Cell(0, 10, utf8_decode($row2['SubTotal']), 0, 1);
        $pdf->Cell(50, 10, utf8_decode($row2['Total']), 0, 1);
        $pdf->Cell(50, 10, utf8_decode($row2['Notes']), 0, 1);
        $pdf->Cell(50, 10, utf8_decode($row2['TermsConditions']),  0, 1);
    }

    // Este es el nombre que le doy en mi local
    $filename = $_SERVER['DOCUMENT_ROOT']."/tecinvoice/rest_api_invoice/invoice-pdf/FACTURA_".$OrganizationID.$InvoiceID.".pdf";
    $pdf->Output($filename,'F');

    $myContainer = 'invoice-pdf';
    // El nombre que se le da en azure
    $blobName = 'aa_FACTURA_'.$OrganizationID.$InvoiceID.'.pdf';
    $filetoUpload = $filename;

    $upload = uploadBlob($myContainer, $blobName, $filetoUpload);
    $obj = json_decode($upload, true);
    $url = $obj['url'];
    sqlsrv_query($conn,"UPDATE INVOICE SET InvoiceURL = '$url' WHERE InvoiceID = $_GET[InvoiceID];");

  
function uploadBlob($myContainer, $blobName, $filetoUpload){
    global $blobClient;
	$content = fopen($filetoUpload, "r");

    try {
        //Upload blob
        $blobClient->createBlockBlob($myContainer, $blobName, $content);
		//genera URL 
		$urlBlob = generateBlobDownloadLinkWithSAS($myContainer, $blobName);
        return '{"url":"'.$urlBlob.'"}';
    } 
    catch (ServiceException $e) {
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message.PHP_EOL;
    }
}

function generateBlobDownloadLinkWithSAS($myContainer, $blobName){

	global $connectionString;

    $settings = StorageServiceSettings::createFromConnectionString($connectionString);
    $accountName = $settings->getName();
    $accountKey = $settings->getKey();

    $helper = new BlobSharedAccessSignatureHelper(
        $accountName,
        $accountKey
    );

    // Refer to following link for full candidate values to construct a service level SAS
    // https://docs.microsoft.com/en-us/rest/api/storageservices/constructing-a-service-sas
    $sas = $helper->generateBlobServiceSharedAccessSignatureToken(
        Resources::RESOURCE_TYPE_BLOB,
        "$myContainer/$blobName",
        'r',                            // Read
        '2030-01-01T08:30:00Z'//,       // A valid ISO 8601 format expiry time
        //'2016-01-01T08:30:00Z',       // A valid ISO 8601 format expiry time
        //'0.0.0.0-255.255.255.255'
        //'https,http'
    );

    $connectionStringWithSAS = Resources::BLOB_ENDPOINT_NAME .
        '='.
        'https://' .
        $accountName .
        '.' .
        Resources::BLOB_BASE_DNS_NAME .
        ';' .
        Resources::SAS_TOKEN_NAME .
        '=' .
        $sas;

    $blobClientWithSAS = BlobRestProxy::createBlobService(
        $connectionStringWithSAS
    );

    // We can download the blob with PHP Client Library
    // downloadBlobSample($blobClientWithSAS);

    // Or generate a temporary readonly download URL link
    $blobUrlWithSAS = sprintf(
        '%s%s?%s',
        (string)$blobClientWithSAS->getPsrPrimaryUri(),
        "$myContainer/$blobName",
        $sas
    );

    return $blobUrlWithSAS;
}

function downloadBlob($blobClient, $blobName){
    try {
        global $myContainer;
        $getBlobResult = $blobClient->getBlob($myContainer, $blobName);
    } catch (ServiceException $e) {
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message.PHP_EOL;
    }

    file_put_contents($blobName, $getBlobResult->getContentStream());
}


?>