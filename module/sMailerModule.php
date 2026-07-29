<?php

use Seiger\sMailer\Controllers\ModuleController;

if (!defined('IN_MANAGER_MODE') || !IN_MANAGER_MODE) {
    die('No access');
}

echo app(ModuleController::class)->index()->render();
