<?php
// api/MailService.php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

// Ensure config is loaded
if (!defined('SMTP_HOST')) {
    $configFile = __DIR__ . '/../config-mail.php';
    if (file_exists($configFile)) {
        require_once $configFile;
    }
}

class MailService
{
    private function getMailer(): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mode = env('MAIL_MODE', 'phpmail');

        if ($mode === 'smtp') {
            // Server settings for SMTP
            $mail->isSMTP();
            $mail->Host = env('SMTP_HOST', 'localhost');
            $mail->SMTPAuth = true;
            $mail->Username = env('SMTP_USER', '');
            $mail->Password = env('SMTP_PASS', '');

            $port = env('SMTP_PORT', 587);
            $mail->Port = $port;

            if ($port == 465) {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
        } else {
            // Native PHP mail()
            $mail->isMail();
        }

        $mail->CharSet = 'UTF-8';

        // Default Sender
        $from = env('FROM_EMAIL', 'noreply@geii.fr');
        if ($mode === 'smtp' && env('SMTP_USER') !== '') {
            $from = env('SMTP_USER');
        }
        $mail->setFrom($from, 'Annuaire GEII');

        return $mail;
    }

    /**
     * Send contact form email to Admin
     */
    public function sendContactEmail(string $fromName, string $fromEmail, string $subject, string $message, ?array $attachment = null): void
    {
        $mail = $this->getMailer();

        // From Visitor (Reply-To)
        $fromAddr = env('FROM_EMAIL', env('SMTP_USER', 'noreply@geii.fr'));

        $mail->setFrom($fromAddr, $fromName . ' (via Contact)');
        $mail->addReplyTo($fromEmail, $fromName);

        // To Admin
        $mail->addAddress(env('ADMIN_EMAIL'));

        // Content
        $mail->Subject = "[Contact] " . $subject;
        $mail->Body = "Nouveau message de : $fromName ($fromEmail)\n\n" . $message;

        // Attachment
        if ($attachment && $attachment['error'] === UPLOAD_ERR_OK) {
            $mail->addAttachment($attachment['tmp_name'], $attachment['name']);
        }

        $mail->send();
    }

    /**
     * Send Verification Email to Student
     */
    public function sendVerificationEmail(string $toEmail, string $prenom, string $token): void
    {
        $mail = $this->getMailer();
        $mail->addAddress($toEmail);

        $verifyLink = "http://" . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['SCRIPT_NAME'])) . "/verify.php?token=" . $token;
        // Cleanup URL
        $verifyLink = preg_replace('#(?<!:)//+#', '/', $verifyLink);

        $mail->Subject = "Vérifiez votre adresse email - Annuaire GEII";
        $mail->Body = "Bonjour $prenom,\n\nMerci d'avoir ajouté votre expérience.\nVeuillez cliquer sur ce lien pour vérifier votre adresse email :\n\n$verifyLink\n\nSi vous n'êtes pas à l'origine de cette action, ignorez cet email.\n\nCordialement,\nL'équipe GEII";

        $mail->send();
    }

    /**
     * Send Notification to Admin about new Experience
     */
    public function sendAdminNotification(string $etudiantNom, string $entrepriseNom): void
    {
        $mail = $this->getMailer();
        $mail->addAddress(env('ADMIN_EMAIL'));

        $mail->Subject = "[Admin] Nouvelle expérience ajoutée";
        $mail->Body = "Une nouvelle expérience a été ajoutée par $etudiantNom chez $entrepriseNom.\n\nConnectez-vous à l'administration pour la valider :\nhttp://" . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['SCRIPT_NAME'])) . "/admin/";

        $mail->send();
    }
}
