<?php

namespace api\Services;

use PSpell\Config;

require_once __DIR__ . '/ErrorHandler.php';

/**
 * Config Helper
 * This class helps with configuration management and migration.
 */
class ConfigCheck {
    /**
     * Verifies that the config file exists and is properly configured.
     * If example config has new keys, it will attempt to migrate them.
     * 
     * @return bool True if configuration is valid
     */
    public static function verifyConfig(): bool {
        // Check if config exists
        if (!file_exists(__DIR__.'/../Config/config.php')) {
            ErrorHandler::handle('CONFIG_MISSING');
            return false;
        }

        require_once __DIR__.'/../Config/config.php';

        $definedCheckKeys = [
            'TENANT_NAME',
            'CONNECT_CODE',
            'DEBUG_MODE',
            'DOCENT_SCHEDULE',
            'ZERMELO_PORTAL_URL',
            'ZERMELO_API_TOKEN'
        ];

        $missingKeys = [];
        foreach ($definedCheckKeys as $key) {
            if (!defined($key)) {
                $missingKeys[] = $key;
            }
        }

        if (!empty($missingKeys)) {
            return self::migrateConfig($missingKeys);
        }
        
        return true;
    }
    
    /**
     * Attempts to automatically migrate missing configuration
     * 
     * @param array $missingKeys The keys that need to be migrated
     * @return bool True if migration was successful
     */
    private static function migrateConfig(array $missingKeys): bool {
        $configFile = __DIR__.'/../Config/config.php';
        $configContent = file_get_contents($configFile);
        $exampleContent = file_get_contents(__DIR__.'/../Config/config.php.default');
        $updated = false;
        $migratedKeys = [];

        if (substr($configContent, 0, 5) !== '<?php') {
            $configContent = '<?php' . $configContent;
        }
        
        foreach ($missingKeys as $key) {
            if (preg_match('/define\([\'"]' . preg_quote($key) . '[\'"]\s*,\s*(.*?)\)\s*;/s', $exampleContent, $matches)) {
                // Add the missing constant to the config file
                $newConstant = "define('" . $key . "', " . $matches[1] . "); // Auto-migrated\n";
                $configContent .= "\n" . $newConstant;
                $updated = true;
                $migratedKeys[] = $key;
            }
        }
        
        if ($updated) {
            // Try to write updated config back
            if (is_writable($configFile)) {
                file_put_contents($configFile, $configContent);
                
                // Log the migration
                error_log('Config file automatically migrated. Added constants: ' . implode(', ', $migratedKeys));
                
                // Reload the config file to apply changes
                include_once $configFile;
                return true;
            } else {
                // Cannot write, must notify
                ErrorHandler::handle('CONFIG_NOT_WRITABLE', 
                    'Missing configuration constants: ' . implode(', ', $missingKeys) .
                    '. Please add these constants to your config.php file.'
                );
                return false;
            }
        }
        
        // No updates needed
        return true;
    }
}