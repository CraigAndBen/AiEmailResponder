<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;

// If your .env is in the same folder as test.php
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "OpenAI Key: " . $_ENV['OPENAI_API_KEY'] . "<br>";
echo "SMTP User: " . $_ENV['SMTP_USER'] . "<br>";
echo "SMTP Pass: " . $_ENV['SMTP_PASS'] . "<br>";
