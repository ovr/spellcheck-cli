<?php
/**
 * @author Patsura Dmitry http://github.com/ovr <talk@dmtry.me>
 */

namespace Ovr\SpellChecker\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('check')
            ->setDescription('Spell check your text')
            ->setDefinition(array(
                new InputOption('language', null, InputOption::VALUE_OPTIONAL, 'Text language', 'ru'),
                new InputOption('ext', null, InputOption::VALUE_OPTIONAL, 'what ext wee need', false),
                new InputArgument('path', InputArgument::REQUIRED),
            ))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        var_dump($input->getArguments());
        var_dump($input->getOptions());
    }
}
