<?php
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
function motion_update() {
    foreach (eqLogic::byType('motion') as $motion) {
        $motion->save();
    }
}
?>
