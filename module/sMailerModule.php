<?php

use Seiger\sMailer\Controllers\sMailerController;

if (!defined('IN_MANAGER_MODE') || !IN_MANAGER_MODE) {
    die('No access');
}

echo app(sMailerController::class)->index()->render();
