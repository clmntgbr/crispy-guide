<?php

namespace App\Command;

use App\Service\GasStationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GasStationClosedCommand extends Command
{
    protected static $defaultName = 'app:gas-station-closed';
    protected static $defaultDescription = 'Checking Gas Station Open/Closed.';

    /** @var GasStationService */
    private $gasStationService;

    public function __construct(GasStationService $gasStationService, string $name = null)
    {
        parent::__construct($name);
        $this->gasStationService = $gasStationService;
    }

    protected function configure(): void
    {
        $this->setDescription(self::getDefaultDescription());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->gasStationService->updateGasStationStatusClosed();

        return Command::SUCCESS;
    }
}
