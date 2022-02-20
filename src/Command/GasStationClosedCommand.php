<?php

namespace App\Command;

use App\Service\CommandService;
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

    /** @var CommandService */
    private $commandService;

    public function __construct(GasStationService $gasStationService, CommandService $commandService, string $name = null)
    {
        parent::__construct($name);
        $this->gasStationService = $gasStationService;
        $this->commandService = $commandService;
    }

    protected function configure(): void
    {
        $this->setDescription(self::getDefaultDescription());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->commandService->start(self::class);

        $this->gasStationService->updateGasStationStatusClosed($this->commandService);

        $this->commandService->end();

        return Command::SUCCESS;
    }
}
