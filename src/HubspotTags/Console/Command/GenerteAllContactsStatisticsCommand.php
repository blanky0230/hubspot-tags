<?php

declare(strict_types=1);

namespace App\HubspotTags\Console\Command;

use HubspotTags\Domain\ValueObject\ContactMailIdentifier;
use HubspotTags\Domain\ValueObject\Email;
use HubspotTags\Integration\HubspotIntegrationService;
use HubspotTags\UseCase\ActivityAggregateJsonPrettyOutput;
use HubspotTags\UseCase\ActivityAggregateTableOutput;
use HubspotTags\UseCase\GetAllContactsCloseAndDemoAggregate;
use HubspotTags\UseCase\GetSingleContactsCloseAndDemoAggregate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerteAllContactsStatisticsCommand extends Command
{
    /**
     * GenerteAllContactsStatisticsCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('generate-report');
    }

    public function configure()
    {
        $this->setDescription('Generate statistics in the scope of one single contact')
            ->addArgument('contact-email', InputArgument::OPTIONAL,
                'The contact email for generating reports in the scope of a single contact.')
            ->addOption('json', 'j', InputOption::VALUE_NONE, 'Generate JSON-Output.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $hapiKey = getenv('HAPIKEY');
        if (!$hapiKey) {
            $output->writeln("'HAPIKEY' environment variable must be provided!");

            return 1;
        }
        $hubby = new HubspotIntegrationService($hapiKey);

        if ($input->getOption('json')) {
            $outputGenerator = new ActivityAggregateJsonPrettyOutput();
        } else {
            $outputGenerator = new ActivityAggregateTableOutput();
        }
        $mailInput = $input->getArgument('contact-email');
        if (is_string($mailInput)) {
            try {
                $mail = new Email($mailInput);
            } catch (\InvalidArgumentException $exception) {
                $output->writeln($exception->getMessage());

                return 1;
            }
            $useCase = new GetSingleContactsCloseAndDemoAggregate($hubby, new ContactMailIdentifier(strval($mail)));
        } else {
            $useCase = new GetAllContactsCloseAndDemoAggregate($hubby);
        }
        $output->write($outputGenerator->generateOutput($useCase->execute()));
    }
}
