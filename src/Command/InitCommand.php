<?php

namespace App\Command;

use App\Service\GasPriceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;

class InitCommand extends Command
{
    protected static $defaultName = 'app:init';
    protected static $defaultDescription = 'Init Project With Datas.';

    const INIT_FILE_PATH = 'public/sql';
    const INIT_FILE_NAME = 'init.sql';
    const GAS_PRICES_DIRECTORY = 'public/sql/gas_prices';

    /** @var KernelInterface */
    private $kernel;

    /** @var EntityManagerInterface */
    private $em;

    /** @var GasPriceService */
    private $gasPriceService;

    public function __construct(KernelInterface $kernel, EntityManagerInterface $em, GasPriceService $gasPriceService, string $name = null)
    {
        parent::__construct($name);
        $this->kernel = $kernel;
        $this->gasPriceService = $gasPriceService;
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this->setDescription(self::getDefaultDescription());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $io->title('doctrine:database:drop');

        $input = new ArrayInput([
            'command' => 'doctrine:database:drop',
            '--force' => '--force',
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $io->title('doctrine:database:create');

        $input = new ArrayInput([
            'command' => 'doctrine:database:create',
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $io->title('doctrine:migrations:migrate');

        $input = new ArrayInput([
            'command' => 'doctrine:migrations:migrate',
            '--no-interaction' => '--no-interaction',
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $io->title('doctrine:fixtures:load');

        $input = new ArrayInput([
            'command' => 'doctrine:fixtures:load',
            '--no-interaction' => '--no-interaction',
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $io->title('Init Sql');

        $finder = new Finder();
        $finder->in(self::INIT_FILE_PATH);
        $finder->name(self::INIT_FILE_NAME);

        foreach( $finder as $file ){
            $content = $file->getContents();
            $stmt = $this->em->getConnection()->prepare($content);
            $stmt->executeQuery();
        }

//        $io->title('Init Gas Prices');
//
//        $directories = scandir(self::GAS_PRICES_DIRECTORY, SCANDIR_SORT_ASCENDING);
//        unset($directories[0]);
//        unset($directories[1]);
//
//
//        $io->progressStart(count($directories));
//
//        foreach ($directories as $directory) {
//            $directoriesDepartments = scandir(self::GAS_PRICES_DIRECTORY . "/$directory", SCANDIR_SORT_ASCENDING);
//            unset($directoriesDepartments[0]);
//            unset($directoriesDepartments[1]);
//            foreach ($directoriesDepartments as $directoryDepartment) {
//                $files = scandir(self::GAS_PRICES_DIRECTORY . "/$directory/$directoryDepartment", SCANDIR_SORT_ASCENDING);
//                foreach ($files as $file) {
//                    $finder = new Finder();
//                    $finder->in(self::GAS_PRICES_DIRECTORY . "/$directory");
//                    $finder->name($file);
//
//                    foreach( $finder as $sql ){
//                        $content = $sql->getContents();
//                        $stmt = $this->em->getConnection()->prepare($content);
//                        $stmt->executeQuery();
//                    }
//                }
//            }
//
//            $io->progressAdvance();
//        }
//
//        $io->progressFinish();

//        $io->title('Update Gas Stations Last Gas Prices');
//
//        $gasStations = $this->em->getRepository(GasStation::class)->findAll();
//        $gasTypes = $this->em->getRepository(GasType::class)->findAll();
//
//        $io->progressStart(count($gasStations));
//
//        foreach ($gasStations as $gasStation) {
//            foreach ($gasTypes as $gasType) {
//                /** @var GasPrice $lastGasPrice */
//                $lastGasPrice = $this->em->getRepository(GasPrice::class)->findLastGasPriceByTypeAndGasStation($gasStation, $gasType);
//                if (null === $lastGasPrice) {
//                    continue;
//                }
//
//                $this->gasPriceService->updateLastGasPrices($gasStation, $lastGasPrice);
//                $this->gasPriceService->updatePreviousGasPrices($gasStation, $lastGasPrice);
//            }
//
//            $this->em->persist($gasStation);
//            $this->em->flush();
//
//            $io->progressAdvance();
//        }
//
//        $io->progressFinish();



        return Command::SUCCESS;
    }
}
