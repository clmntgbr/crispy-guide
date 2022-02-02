<?php

namespace App\Command;

use App\Service\GasPriceService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GasPriceYearCommand extends Command
{
    protected static $defaultName = 'app:gas-price-year';
    protected static $defaultDescription = 'Creating Year GasPrices';

    /** @var GasPriceService */
    private $gasPriceService;

    public function __construct(GasPriceService $gasPriceService, string $name = null)
    {
        parent::__construct($name);
        $this->gasPriceService = $gasPriceService;
    }

    protected function configure(): void
    {
        $this->setDescription(self::getDefaultDescription());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->gasPriceService->updateYearGasPrices();

        return Command::SUCCESS;
    }
}
