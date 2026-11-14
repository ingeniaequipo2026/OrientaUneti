<?php
/**
 * Redirección transparente al directorio público
 */
chdir(__DIR__ . '/public');
require_once __DIR__ . '/public/index.php';
