<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Service\DistanceCalculatorService;

class DistanceCalculatorCommand extends Command
{
    public function __construct(private DistanceCalculatorService $distanceCalculatorService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:distance-calculate')
            ->setDescription('Script to calculate the distance')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $response = $this->distanceCalculatorService->process();
            $output->writeln($response);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('An error occurred: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            
            return Command::FAILURE;
        }
    }
}
