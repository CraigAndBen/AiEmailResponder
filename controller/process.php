<?php
require '../vendor/autoload.php'; // PHPMailer & Dotenv
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// === Load .env ===
$dotenv = Dotenv::createImmutable(__DIR__ . "/../"); // path to project root
$dotenv->load();

// === Config from .env ===
$apiKey   = $_ENV['OPENAI_API_KEY'] ?? '';
$smtpUser = $_ENV['SMTP_USER'] ?? '';
$smtpPass = $_ENV['SMTP_PASS'] ?? '';

// === Function: Generate AI Reply ===
function generateAIReply($apiKey, $name, $message)
{
    $models = ["gpt-4o-mini", "gpt-4o", "gpt-3.5-turbo"]; // fallback order
    $reply = "Thank you for reaching out!";

    foreach ($models as $model) {
        $payload = [
            "model" => $model,
            "messages" => [
                ["role" => "system", "content" => "You are a polite company assistant replying to customer messages."],
                ["role" => "user", "content" => "Message from {$name}: {$message}"]
            ]
        ];

        $ch = curl_init("https://api.openai.com/v1/chat/completions");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$apiKey}"
            ],
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        var_dump($response);
        if ($httpCode === 200 && $response) {
            $result = json_decode($response, true);
            if (!empty($result['choices'][0]['message']['content'])) {
                $reply = $result['choices'][0]['message']['content'];
                break; // ✅ stop if successful
            }
        }
        // else: try next model
    }

    return $reply;
}

// === Function: Send Email ===
function sendMail($to, $toName, $subject, $body, $originalMessage = '', $smtpUser, $smtpPass)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = "smtp.gmail.com";
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpUser;
        $mail->Password   = $smtpPass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom($smtpUser, "AI Auto Responder");
        $mail->addAddress($to, $toName);

        $mail->isHTML(true);
        $mail->Subject = $subject;

        $htmlBody = "<p>{$body}</p>";
        if ($originalMessage) {
            $htmlBody .= "<hr><p><strong>Original Message:</strong><br>" . nl2br(htmlspecialchars($originalMessage)) . "</p>";
        }

        $mail->Body = $htmlBody;
        return $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

// === 1. Handle Website Form Submission ===
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'])) {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name && $email && $message) {
        $aiReply = generateAIReply($apiKey, $name, $message);

        // Send email including original message
        sendMail($email, $name, "Auto Reply from Company", $aiReply, $message, $smtpUser, $smtpPass);
        echo "✅ Reply sent to " . htmlspecialchars($email);
    } else {
        http_response_code(400);
        echo "❌ Missing required fields.";
    }
}

// === 2. Gmail Inbox Auto-Reply (Manual Trigger) ===
if (isset($_GET['check_mail']) && $_GET['check_mail'] === '1') {
    $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
    $inbox = @imap_open($hostname, $smtpUser, $smtpPass);

    if (!$inbox) {
        echo imap_last_error();
    }

    if ($inbox) {
        $emails = imap_search($inbox, 'UNSEEN'); // only unread emails
        if ($emails) {
            rsort($emails);
            foreach ($emails as $email_number) {
                $overview = imap_fetch_overview($inbox, $email_number, 0);
                $message  = imap_fetchbody($inbox, $email_number, 1);

                $from    = $overview[0]->from;
                $subject = $overview[0]->subject ?: "Your message";

                if (preg_match('/<(.+?)>/', $from, $matches)) {
                    $fromEmail = $matches[1];
                } else {
                    $fromEmail = $from;
                }

                // Generate AI reply
                $aiReply = generateAIReply($apiKey, $from, strip_tags($message));

                // Send reply including original message
                sendMail($fromEmail, $from, "Re: " . $subject, $aiReply, $message, $smtpUser, $smtpPass);

                // Mark email as seen
                imap_setflag_full($inbox, $email_number, "\\Seen");
            }
        }
        imap_close($inbox);
        echo "✅ Checked and replied to unread emails.";
    } else {
        echo "❌ Cannot connect to Gmail IMAP: " . imap_last_error();
    }
}
