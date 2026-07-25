<?php
/**
 * Global Delivered Logistics - Mail Configuration
 * 
 * SMTP settings for sending emails via PHPMailer.
 * IMAP settings for reading emails.
 */

return [
    'smtp' => [
        'host'       => 'mail.globaldelivered.biz',
        'port'       => 465,
        'username'   => 'track@globaldelivered.biz',
        'password'   => '@Nelima2016',
        'encryption' => 'ssl',
        'from_email' => 'track@globaldelivered.biz',
        'from_name'  => 'Global Delivered Logistics',
    ],
    'imap' => [
        'host'       => 'mail.globaldelivered.biz',
        'port'       => 993,
        'username'   => 'track@globaldelivered.biz',
        'password'   => '@Nelima2016',
        'encryption' => 'ssl',
    ],
];
