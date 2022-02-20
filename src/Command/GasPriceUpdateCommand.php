<?php

namespace App\Command;

use App\Service\CommandService;
use App\Service\GasPriceService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GasPriceUpdateCommand extends Command
{
    protected static $defaultName = 'app:gas-price-update';
    protected static $defaultDescription = 'Creating Up To Date GasPrices.';

    /** @var GasPriceService */
    private $gasPriceService;

    /** @var CommandService */
    private $commandService;

    public function __construct(GasPriceService $gasPriceService, CommandService $commandService, string $name = null)
    {
        parent::__construct($name);
        $this->gasPriceService = $gasPriceService;
        $this->commandService = $commandService;
    }

    protected function configure(): void
    {
        $this->setDescription(self::getDefaultDescription());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->commandService->start(self::class);

        $this->gasPriceService->updateInstantGasPrices($this->commandService);

        $this->commandService->end();

        return Command::SUCCESS;
    }
}
