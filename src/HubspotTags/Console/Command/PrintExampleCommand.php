<?php

declare(strict_types=1);

namespace App\HubspotTags\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class PrintExampleCommand extends Command
{
    public function __construct()
    {
        parent::__construct('example');
    }

    protected function configure()
    {
        $this->setDescription('Display this example output.')
            ->setHelp('Showing options and general usage of this program.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            "Simple command line utility to get statistics for custom 'tags' from your HubSpot CRM-Contacts.",
            "Currently supported are 'DEMO' and 'CLOSE'. If you have any activity for a CRM-Contact that contains these strings, they're counted as a Close- or Demo-Tag respectively.",
            '',
            'The statistics are grouped by days. Days that have NO tag will not be shown.',
            '',
            'Example output:',
            '',
            'hubspot-tags.php',
            '|Day        |CLOSE |DEMO  |',
            '|2017-02-07 |1     |2     |',
            '|2017-02-08 |1     |0     |',
            '',
            'Additional options:',
            '',
            '--contact or -c <contact@email.com> will create the statistics in the scope of only one single contact instead of all.',
            "Example: 'hubspot-tags.php --contact foo@bar.com'",
            '--json procudes the output as JSON in pretty-print instead of printing a table to stdout.',
        ]);
    }
}
