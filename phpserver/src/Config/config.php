<?php
// config.php

// Securely loads configuration settings from AWS Secrets Manager or Parameter Store
// (Composer autoload removed for local XAMPP use)

use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;

class Config {
    private static $secrets = null;

    public static function loadSecrets() {
        if (self::$secrets !== null) return self::$secrets;

        // Prefer AWS SecretsManager if env vars are set, else use .env.local.php for local dev
        if (getenv('AWS_ACCESS_KEY_ID') && getenv('AWS_SECRET_ACCESS_KEY')) {
            $client = new SecretsManagerClient([
                'region' => getenv('AWS_REGION') ?: 'ap-south-1',
                'version' => 'latest',
                'credentials' => [
                    'key' => getenv('AWS_ACCESS_KEY_ID'),
                    'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
                ],
            ]);
            $secretName = getenv('DB_SECRET_NAME') ?: 'prod/db/charusat';
            try {
                $result = $client->getSecretValue(['SecretId' => $secretName]);
                $secret = json_decode($result['SecretString'], true);
                self::$secrets = $secret;
                return $secret;
            } catch (AwsException $e) {
                error_log('AWS SecretsManager error: ' . $e->getMessage());
                throw new Exception('Could not load DB credentials');
            }
        } else {
            // Local dev: load from .env.local.php
            $local = require __DIR__ . '/.env.local.php';
            self::$secrets = $local;
            return $local;
        }
    }

    public static function get($key) {
        $secrets = self::loadSecrets();
        return $secrets[$key] ?? null;
    }
}

// Usage example:
// $dbHost = Config::get('DB_HOST');
// $kmsKeyArn = Config::get('KMS_KEY_ARN');
