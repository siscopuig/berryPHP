<?php

/*
 * http://whatismyipaddress.com/smtp -> some info about SMTP
 * http://phpmailer.github.io/PHPMailer/classes/PHPMailer.html
 *
 * */

class Mail
{
    public $error;

    /**
     * Send mail from php in built mail function
     *
     * @param $emailTo
     * @param $subject
     * @param $headers
     * @param $body
     * @return bool
     */
    public function sendMail($emailTo, $subject, $headers, $body)
    {
        $mail = mail($emailTo, $subject, $headers, $body);

        if ($mail) {
            return true;
        } else {
            $this->error = 'An unknown error has been produced';
            return false;
        }
    }

    /**
     * Send mail with phpMailer - if any error given is grab and display for debugging
     *
     * @param $user_email
     * @param $from_email
     * @param $from_name
     * @param $subject
     * @param $body
     * @return bool
     * @throws Exception
     * @throws phpmailerException
     */
    public function sendMailPhpMailer($user_email, $from_email, $from_name, $subject, $body)
    {
        $mail = new PHPMailer();

        if ((Config::get('EMAIL_SMTP_AUTH'))) {
            // set mailer to use SMTP
            $mail->isSMTP();
            // enable SMTP authentication
            $mail->SMTPAuth = true;
            /*
             * SMTP class debug output mode
             *
             * 0 -> no output
             * 1 -> commands
             * 2 -> data and commands
             * 3 -> as 2 plus connection status
             * 4 -> low-level data output
             * */
            $mail->SMTPDebug = 0;

            // the secure connection prefix, options: "", "ssl" or "tls"
            $mail->SMTPSecure = 'ssl';

            // set SMTP provider's credentials
            $mail->Host = Config::get('EMAIL_SMTP_HOST');
            $mail->Username = Config::get('EMAIL_SMTP_USERNAME');
            $mail->Password = Config::get('EMAIL_SMTP_PASSWORD');
            $mail->Port = Config::get('EMAIL_SMTP_PORT');

        } else {
            // send messages using PHP's mail() function.
            $mail->isMail();
        }

        // properties of email
        $mail->From = $from_email;
        $mail->FromName = $from_name;
        $mail->addAddress($user_email);
        $mail->Subject = $subject;
        $mail->Body = $body;
        // some options that could might possible to add..
        // $mail->addReplyTo('reply@siscopuig.com', 'Reply address');
        // $mail->addCC('sisco@siscopuig.com', 'Sisco Puig');

        // send mail
        if ($mail->Send()) {
            return true;
        } else {
            // no mail was sent, grab errors
            $this->error = $mail->ErrorInfo;
            return false;
        }
    }
}