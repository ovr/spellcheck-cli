<?php
/**
 * @author Patsura Dmitry http://github.com/ovr <talk@dmtry.me>
 */

namespace Ovr\SpellChecker\Command;


use Exception;
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
        $path = $input->getArgument('path');

        if (is_file($path)) {
            if (!is_readable($path)) {
                throw new Exception('File is not readable.');
            }

            $content = file_get_contents($path);

            /**
             * @todo Will rewrite to provider with social connect lib soon
             */
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, 0);

            curl_setopt($curl, CURLOPT_URL, 'http://speller.yandex.net/services/spellservice.json/checkText');
            curl_setopt($curl, CURLOPT_POSTFIELDS, 'language='.$input->getOption('language').'&text='.$content);

            $out = curl_exec($curl);
            echo $out;
            curl_close($curl);
        } else {
            throw new Exception('$path argument must be a file path (dir is not supported).');
        }
    }
}
