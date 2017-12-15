<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UtilsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class TranslationLogExtractorCommand extends ContainerAwareCommand
{
    private $translations = [];
    private $supportedFormats = ['JSON', 'CSV', 'XML', 'YML', 'YAML', 'CONSOLE'];

    protected function configure()
    {
        $this
            ->setName('blast:translations:extract')
            ->setDescription('Extract missing translation from dev.log')
            ->addOption(
                'format',
                'f',
                InputOption::VALUE_OPTIONAL,
                'The expected output format',
                'CONSOLE'
            )
            ->addOption(
                'purge',
                'p',
                InputOption::VALUE_NONE,
                'Removes the parsed log entries'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $format = strtoupper($input->getOption('format'));
        $purge = $input->getOption('purge');

        if (!in_array($format, $this->supportedFormats)) {
            $io->error(sprintf('Output format %s is not supported. Allowed formats: %s', $format, implode(',', $supportedFormats)));

            return;
        }

        if ($format === 'CONSOLE') {
            $io->title('Extracting missing translations from dev.log');
        }

        $kernel = $this->getContainer()->get('kernel');
        $env = $kernel->getEnvironment();

        $logDir = $this->getContainer()->get('kernel')->getLogDir();
        $logFile = $logDir . '/' . $env . '.log';

        if (is_file($logFile)) {
            $this->parseLog($logFile, $io, $purge);
            if (count($this->translations) > 0) {
                $this->displayOutputResult($format, $io);
            }
        } else {
            $io->error(sprintf('Cannot find dev.log in %s', $logDir));
        }
    }

    private function parseLog($pathname, $io, $purge)
    {
        if ($purge) {
            $handleTmp = fopen($pathname . '.tmp', 'w');
        }

        $handle = fopen($pathname, 'r');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (substr($line, 22, 19) === 'translation.WARNING') {
                    $object = null;
                    preg_match('/(\{.*\})\W\[\]$/', $line, $object);

                    if (count($object) > 1) {
                        $objectStr = $object[1];
                        $sign = md5($objectStr);
                        $object = json_decode($objectStr);

                        if (!array_key_exists($sign, $this->translations)) {
                            $this->translations[$sign] = $object;
                        }
                    }

                    if ($purge) {
                        if (flock($handle, LOCK_EX)) {
                            $writeSuccess = fwrite($handle, ' ');
                            flock($handle, LOCK_UN);
                        }
                    }
                } elseif ($purge) {
                    fputs($handleTmp, $line);
                }
            }

            if ($purge) {
                fclose($handleTmp);
                rename($pathname . '.tmp', $pathname);
            }

            fclose($handle);

            usort($this->translations, function ($a, $b) {
                return strcmp(strtolower($a->id), strtolower($b->id));
            });
        } else {
            $io->error(sprintf('Can\'t open file %s', $pathname));
        }
    }

    private function displayOutputResult($format, $io)
    {
        switch ($format) {
            case 'CONSOLE':
            $this->displayConsoleResult($io);
            break;

            case 'JSON':
            $this->displayJsonResult($io);
            break;

            case 'YML':
            case 'YAML':
            $this->displayYmlResult($io);
            break;

            case 'CSV':
            $this->displayCsvResult($io);
            break;

            case 'XML':
            $this->displayXmlResult($io);
            break;
        }
    }

    private function displayConsoleResult($io)
    {
        array_walk($this->translations, function ($object, $key) {
            $this->translations[$key] = (array) $object;
        });

        $io->table(
            array_keys($this->translations[key($this->translations)]),
            $this->translations
        );
    }

    private function displayJsonResult($io)
    {
        $io->text('[');
        $totalTranslations = count($this->translations);
        $count = 0;
        foreach ($this->translations as $sign => $translation) {
            $io->text(json_encode($translation, JSON_FORCE_OBJECT) . ($count < $totalTranslations - 1 ? ',' : ''));
            $count++;
        }
        $io->text(']');
    }

    private function displayYmlResult($io)
    {
        array_walk($this->translations, function ($object, $key) use ($io) {
            $io->text(Yaml::dump($object->id, 0, 4, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK) . ': ~');
        });
    }

    private function displayCsvResult($io)
    {
        array_walk($this->translations, function ($object, $key) use ($io) {
            $out = fopen('php://output', 'w');
            fputcsv($out, (array) $object);
            fclose($out);
        });
    }

    private function displayXmlResult($io)
    {
        $xml = new \SimpleXMLElement('<translations/>');
        array_walk_recursive($this->translations, function ($item, $key) use ($xml) {
            $trans = $xml->addChild('translation');
            foreach ($item as $key => $value) {
                $trans->addChild($key, $value);
            }
        });
        $dom = new \DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        echo $dom->saveXML();
    }
}
