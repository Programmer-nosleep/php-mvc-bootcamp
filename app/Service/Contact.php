<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email as EmailMessage;

use function App\escape;
use function App\site_name;

final class Contact
{
    public function sendEmailToSiteOwner(array $details): bool
    {
        $name = escape($details['name'] ?? '');
        $email = escape($details['email'] ?? '');
        $message = escape($details['message'] ?? '');
        $phoneNumber = (string)($details['phoneNumber'] ?? '');

        if ($phoneNumber !== '' && strlen($phoneNumber) > 5) {
            $message .= "\n\nPhone Number: " . escape($phoneNumber);
        }

        try {
            $dsn = $_ENV['MAILER_DSN'] ?? 'null://null';
            $adminEmail = (string)($_ENV['ADMIN_EMAIL'] ?? '');
            if ($adminEmail === '') {
                return false;
            }

            $transport = Transport::fromDsn($dsn);
            $mailer = new Mailer($transport);

            $emailMessage = new EmailMessage();
            $emailMessage->from(new Address($email, $name));
            $emailMessage->to(new Address($adminEmail, site_name()));
            $emailMessage->subject('Contact Form');
            $emailMessage->priority(EmailMessage::PRIORITY_NORMAL);
            $emailMessage->text($message);

            $mailer->send($emailMessage);

            return true;
        } catch (TransportExceptionInterface $error) {
            error_log($error->getMessage());

            return false;
        }
    }
}
