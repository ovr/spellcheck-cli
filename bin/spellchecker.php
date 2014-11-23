<?php
/**
 * @author Patsura Dmitry http://github.com/ovr <talk@dmtry.me>
 */

include_once __DIR__ . '/../vendor/autoload.php';

use \Ovr\SpellChecker\Application;

$console = new Application();
$console->add(new \Ovr\SpellChecker\Command\CheckCommand());
$console->setDefaultCommand('check');
$console->run();