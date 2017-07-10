<?php
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
function motion_update() {
    foreach (eqLogic::byType('motion') as $motion) {
        $motion->save();
    }
}
function motion_remove() {
    exec('sudo /etc/init.d/motion stop');
    exec('sudo rm -rf /usr/local/src/motion/');
    exec('sudo rm -rf /etc/motion/');
    exec('sudo groupdel motion');
    exec('sudo rm /etc/init.d/motion');
    exec('sudo rm /etc/rc*.d/*motion');
}
?>
