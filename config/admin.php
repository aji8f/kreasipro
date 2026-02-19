<?php
/**
 * Admin Configuration
 * Store sensitive admin credentials here.
 * 
 * Default User: admin
 * Default Pass: admin123 (Change this in production!)
 */

if (!defined('KREASI_PRO_LOADED')) {
    die('Direct access not permitted');
}

return [
    'username' => 'admin',
    // Hash for 'admin123'
    'password_hash' => '$2y$10$mtXSwfGfQjMuuoE5NJ/Zc.LIxp4vWFed3V2swMP65UdF.eTZeWUc.'
];
