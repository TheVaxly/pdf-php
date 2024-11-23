<?php
require 'vendor/autoload.php'; // Composer: TCPDF ja PHPMailer
require 'db.php'; // MySQLi ühendus
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Vormist andmete saamine
$saaja_nimi = htmlspecialchars($_POST['saaja_nimi']);
$saaja_email = filter_var($_POST['saaja_email'], FILTER_VALIDATE_EMAIL);
$sonum = htmlspecialchars($_POST['sonum']);

if (!$saaja_email) {
    die("Ebasobiv e-posti aadress.");
}

// Kontrolli viimase 30 minuti saatmist
$stmt = $conn->prepare("SELECT COUNT(*) FROM emails_sent WHERE email = ? AND sent_at > NOW() - INTERVAL 30 MINUTE");
$stmt->bind_param('s', $saaja_email);
$stmt->execute();
$stmt->fetch();
$stmt->close();

// PDF loomine
$pdf_file = generatePDF($saaja_nimi, $sonum);

// Emaili saatmine
sendEmail($saaja_email, $saaja_nimi, $pdf_file);

// Logi andmebaasi
$stmt = $conn->prepare("INSERT INTO emails_sent (email) VALUES (?)");
$stmt->bind_param('s', $saaja_email);
$stmt->execute();
$stmt->close();

echo "Kiri saadetud edukalt!";

// PDF loomise funktsioon
function generatePDF($name, $message) {
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);
    $html = "
        <h1 style='color: red;'>Häid jõule, $name!</h1>
        <p style='font-size: 14px;'>$message</p>
    ";
    $pdf->writeHTML($html);
    $file_path = __DIR__."/generated_pdfs/joulutervitus_" . time() . ".pdf";
    ob_clean();
    $pdf->Output($file_path, 'F');
    return $file_path;
}

function sendEmail($to, $name, $pdf_path) {
    $mail = new PHPMailer(true);
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    try {
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USERNAME'];
        $mail->Password = $_ENV['SMTP_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = $_ENV['SMTP_PORT'];

        $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);
        $mail->addAddress($to, $name);
        $mail->addAttachment($pdf_path);

        $mail->isHTML(true);
        $mail->Subject = 'Häid jõule!';
        $mail->Body = "<h1>Tere, $name!</h1><p>Siin on sinu jõulutervitus. Vaata manust!</p>";

        $mail->send();
        echo "Kiri saadetud edukalt!";
    } catch (Exception $e) {
        echo "Kiri ei saadetud: {$mail->ErrorInfo}";
    }
}
?>
