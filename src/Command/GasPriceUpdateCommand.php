<?php

namespace App\Command;

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

    public function __construct(GasPriceService $gasPriceService, string $name = null)
    {
        parent::__construct($name);
        $this->gasPriceService = $gasPriceService;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creating Up To Date GasPrices For Each GasStations.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->gasPriceService->update();

        return Command::SUCCESS;
    }
}
