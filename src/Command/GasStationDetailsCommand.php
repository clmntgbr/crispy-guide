<?php

namespace App\Command;

use App\Service\GasStationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GasStationDetailsCommand extends Command
{
    protected static $defaultName = 'app:gas-station-details';
    protected static $defaultDescription = 'Finding Details For Gas Station.';

    /** @var GasStationService */
    private $gasStationService;

    public function __construct(GasStationService $gasStationService, string $name = null)
    {
        parent::__construct($name);
        $this->gasStationService = $gasStationService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->gasStationService->update();

        return Command::SUCCESS;
    }
}
