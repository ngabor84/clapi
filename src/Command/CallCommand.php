<?php declare(strict_types=1);

namespace Clapi\Command;

use Clapi\ApiCall\ApiCall;
use Clapi\ApiCall\ApiCallClientBuilder;
use Clapi\ApiCall\ApiCallOptionParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CallCommand extends Command
{
    protected function configure(): void
    {
        $this->setName("call")
            ->setDescription("Create an http request")
            ->addArgument('URL', InputArgument::REQUIRED, 'The URL of the API endpoint you want to call')
            ->addOption('method', 'X', InputOption::VALUE_REQUIRED, "Set request method", 'GET')
            ->addOption('payload', 'd', InputOption::VALUE_REQUIRED, "Add request payload")
            ->addOption('auth', 'a', InputOption::VALUE_REQUIRED, "Set authentication type")
            ->addOption('key', 'u', InputOption::VALUE_REQUIRED, "Set authentication key")
            ->addOption('secret', 'p', InputOption::VALUE_REQUIRED, "Set authentication secret")
            ->addOption('scope', 's', InputOption::VALUE_REQUIRED, "Set authentication scope")
            ->addOption('header', 'H', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, "Add custom header", []);
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $optionParser = new ApiCallOptionParser();
        $options = $optionParser->parse($input);
        $clientBuilder = new ApiCallClientBuilder();
        $call = new ApiCall($clientBuilder);

        $response = $call->execute($options);

        $output->writeln('Response: ' . $response->getBody()->getContents());
    }
}
