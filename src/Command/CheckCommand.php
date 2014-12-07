<?php
/**
 * @author Patsura Dmitry http://github.com/ovr <talk@dmtry.me>
 */

namespace Ovr\SpellChecker\Command;


use Exception;
use RecursiveDirectoryIterator;
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
                new InputOption('language', 'lang', InputOption::VALUE_OPTIONAL, 'Text language', 'ru,en'),
                new InputOption('ext', null, InputOption::VALUE_OPTIONAL, 'what ext wee need', []),
                new InputOption('exit-code-with-miss-takes', null, InputOption::VALUE_OPTIONAL, 'Programm exit code when it has misstakes in text', 0),
                new InputArgument('path', InputArgument::REQUIRED),
            ))
        ;
    }

    protected function checkFile($path, InputInterface $input, OutputInterface $output)
    {
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

        $hasMistakes = false;

        $response = curl_exec($curl);
        if ($response) {
            $result = json_decode($response);

            if (count($result) > 0) {
                $hasMistakes = true;

                /** @var \Symfony\Component\Console\Helper\Table $table */
                $table = $this->getHelper('table');
                $table->setHeaders(array('Word', 'Need to be', 'Type', 'Line'));

                foreach ($result as $mistake) {
                    $table->addRow(array(
                        $mistake->word,
                        isset($mistake->s) ? (is_array($mistake->s) ? implode(',', $mistake->s) : $mistake->s) : '*',
                        isset($mistake->code) ? $mistake->code : '*',
                        $mistake->row
                    ));
                }

                $table->render($output);
            }
        }

        curl_close($curl);

        return $hasMistakes;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');
        $hasMistakes = false;

        if (is_file($path)) {
            if (!is_readable($path)) {
                throw new Exception('File is not readable.');
            }

            $hasMistakes = $this->checkFile($path, $input, $output);
        } else {
            if (!is_dir($path)) {
                throw new Exception('$path argument is not a path.');
            }

            $it = new RecursiveDirectoryIterator($path);

            $ext = $input->getOption('ext');
            if (is_string($ext)) {
                $ext = explode(',', $ext);
            }

            /** @var \SplFileInfo $fileinfo */
            foreach ($it as $fileinfo) {
                if ($fileinfo->isFile()) {
                    if (count($ext) > 0 && !in_array($fileinfo->getExtension(), $ext)) {
                        continue;
                    }

                    $hasMistakes = $this->checkFile($fileinfo->getPathname(), $input, $output);
                }
            }
        }

        if ($hasMistakes) {
            exit($input->getOption('exit-code-with-miss-takes'));
        }
    }
}
