#!/usr/bin/php
<?php

use App\HubspotTags\Console\Command\GenerateSingleContactStatisticsCommand;
use App\HubspotTags\Console\Command\GenerteAllContactsStatisticsCommand;
use App\HubspotTags\Console\Command\PrintExampleCommand;
use Symfony\Component\Console\Application;

require_once __DIR__ .'/vendor/autoload.php';
$hubspotApiKey = ('HAPIKEY');

if (!$hubspotApiKey) {
    die('HAPIKEY Variable must be set!\n');
}


$application = new Application();
$application->add(new PrintExampleCommand());
$all = new GenerteAllContactsStatisticsCommand();
$application->add($all);
$application->run();

