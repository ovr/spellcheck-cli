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
            ->addArgument(
                'language',
                InputArgument::OPTIONAL,
                'Who do you want to greet?',
                'ru'
            )
            ->addArgument(
                'file',
                null,
                InputOption::VALUE_REQUIRED,
                'If set, the task will yell in uppercase letters'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $language = $input->getArgument('language');
        var_dump($language);
    }
}
