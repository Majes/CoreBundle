<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Majes\CoreBundle\Services;


use Symfony\Bundle\FrameworkBundle\Translation\TranslationLoader;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Translation\Catalogue\MergeOperation;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;
/**
 * Translator.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @api
 */
class CoreTranslator
{
    const MESSAGE_MISSING = 0;
    const MESSAGE_UNUSED = 1;
    const MESSAGE_EQUALS_FALLBACK = 2;
    private $_locale;
    private $_container;

    public function __construct($locale, $container){
      $this->_locale = $locale;
      $this->_container = $container;
    }
    /**
    * {@inheritdoc}
    */
    protected function configure()
    {
    //    $this
    //        ->setName('debug:translation')
    //        ->setDefinition(array(
    //            new InputArgument('locale', InputArgument::REQUIRED, 'The locale'),
    //            new InputArgument('bundle', InputArgument::OPTIONAL, 'The bundle name or directory where to load the messages, defaults to app/Resources folder'),
    //            new InputOption('domain', null, InputOption::VALUE_OPTIONAL, 'The messages domain'),
    //            new InputOption('only-missing', null, InputOption::VALUE_NONE, 'Displays only missing messages'),
    //            new InputOption('only-unused', null, InputOption::VALUE_NONE, 'Displays only unused messages'),
    //            new InputOption('all', null, InputOption::VALUE_NONE, 'Load messages from all registered bundles'),
    //        ))
    //        ->setDescription('Displays translation messages information');
    }
    /**
    * {@inheritdoc}
    */
    public function workTrads($bundle = 'all')
    {
    //    $output = new SymfonyStyle($input, $output);
       $locale = $this->_locale;
       $domain = null;
       /** @var TranslationLoader $loader */
       $loader = $this->_container->get('translation.loader');
       /** @var Kernel $kernel */
       $kernel = $this->_container->get('kernel');
       // Define Root Path to App folder
       $transPaths = array($kernel->getRootDir().'/Resources/');
       // Override with provided Bundle info

       if ($bundle != 'all') {
           try {
               $bundle = $kernel->getBundle($bundle);
               $transPaths = array(
                   $bundle->getPath().'/Resources/',
                   sprintf('%s/Resources/%s/', $kernel->getRootDir(), $bundle->getName()),
               );
           } catch (\InvalidArgumentException $e) {
               // such a bundle does not exist, so treat the argument as path
               $transPaths = array($input->getArgument('bundle').'/Resources/');
               if (!is_dir($transPaths[0])) {
                   throw new \InvalidArgumentException(sprintf('"%s" is neither an enabled bundle nor a directory.', $transPaths[0]));
               }
           }
       } else {
           foreach ($kernel->getBundles() as $bundle) {
               $transPaths += array($bundle->getPath().'/Resources/');
               $transPaths += array(sprintf('%s/Resources/%s/', $kernel->getRootDir(), $bundle->getName()));
           }
       }
       // Extract used messages
       $extractedCatalogue = $this->extractMessages($locale, $transPaths);
       // Load defined messages
       $currentCatalogue = $this->loadCurrentMessages($locale, $transPaths, $loader);

       // Merge defined and extracted messages to get all message ids
       $mergeOperation = new MergeOperation($extractedCatalogue, $currentCatalogue);
       $allMessages = $mergeOperation->getResult()->all($domain);
       if (null !== $domain) {
           $allMessages = array($domain => $allMessages);
       }
       // No defined or extracted messages
       if (empty($allMessages) || null !== $domain && empty($allMessages[$domain])) {
           $outputMessage = sprintf('No defined or extracted messages for locale "%s"', $locale);
           if (null !== $domain) {
               $outputMessage .= sprintf(' and domain "%s"', $domain);
           }
        //    $output->warning($outputMessage);
           return $outputMessage;
       }

       // Load the fallback catalogues
       $fallbackCatalogues = $this->loadFallbackCatalogues($locale, $transPaths, $loader);
       // Display header line
    //    $headers = array('State', 'Domain', 'Id', sprintf('Message Preview (%s)', $locale));
    //    foreach ($fallbackCatalogues as $fallbackCatalogue) {
        //    $headers[] = sprintf('Fallback Message Preview (%s)', $fallbackCatalogue->getLocale());
    //    }
       $traductions = array();
       // Iterate all message ids and determine their state
       foreach ($allMessages as $domain => $messages) {
           foreach (array_keys($messages) as $messageId) {
               $value = $currentCatalogue->get($messageId, $domain);
               $states = array();
               if ($extractedCatalogue->defines($messageId, $domain)) {
                   if (!$currentCatalogue->defines($messageId, $domain)) {
                       $states[] = self::MESSAGE_MISSING;
                   }
               } elseif ($currentCatalogue->defines($messageId, $domain)) {
                   $states[] = self::MESSAGE_UNUSED;
               }
            //    if (!in_array(self::MESSAGE_UNUSED, $states) && true === $input->getOption('only-unused')
            //        || !in_array(self::MESSAGE_MISSING, $states) && true === $input->getOption('only-missing')) {
            //        continue;
            //    }
               foreach ($fallbackCatalogues as $fallbackCatalogue) {
                   if ($fallbackCatalogue->defines($messageId, $domain) && $value === $fallbackCatalogue->get($messageId, $domain)) {
                       $states[] = self::MESSAGE_EQUALS_FALLBACK;
                       break;
                   }
               }
               $row = array('state' => array_sum($states), 'formatState' => $this->formatStates($states), 'domain' => $domain, 'id' => $this->formatId($messageId), 'sanitized' => $this->sanitizeString($value));
               foreach ($fallbackCatalogues as $fallbackCatalogue) {
                   $row[] = $this->sanitizeString($fallbackCatalogue->get($messageId, $domain));
               }
               $rows[] = $row;
           }
       }
       return $rows;
    }
    private function formatState($state)
    {
       if (self::MESSAGE_MISSING === $state) {
           return '<div class="label label-warning pull-right">Missing</div>';
       }
       if (self::MESSAGE_UNUSED === $state) {
           return '<div class="label label-info pull-right">Unused</div>';
       }
       if (self::MESSAGE_EQUALS_FALLBACK === $state) {
           return '<div class="label label-info pull-right">Fallback</div>';
       }
       return $state;
    }
    private function formatStates(array $states)
    {
       $result = array();
       foreach ($states as $state) {
           $result[] = $this->formatState($state);
       }
       return implode(' ', $result);
    }

    private function formatId($id)
    {
       return sprintf('<fg=cyan;options=bold>%s</fg=cyan;options=bold>', $id);
    }
    private function sanitizeString($string, $length = 40)
    {
       $string = trim(preg_replace('/\s+/', ' ', $string));
       if (function_exists('mb_strlen') && false !== $encoding = mb_detect_encoding($string)) {
           if (mb_strlen($string, $encoding) > $length) {
               return mb_substr($string, 0, $length - 3, $encoding).'...';
           }
       } elseif (strlen($string) > $length) {
           return substr($string, 0, $length - 3).'...';
       }
       return $string;
    }
    /**
    * @param string $locale
    * @param array  $transPaths
    *
    * @return MessageCatalogue
    */
    private function extractMessages($locale, $transPaths)
    {
       $extractedCatalogue = new MessageCatalogue($locale);
       foreach ($transPaths as $path) {
           $path = $path.'views';
           if (is_dir($path)) {
               $this->_container->get('translation.extractor')->extract($path, $extractedCatalogue);
           }
       }
       return $extractedCatalogue;
    }
    /**
    * @param string            $locale
    * @param array             $transPaths
    * @param TranslationLoader $loader
    *
    * @return MessageCatalogue
    */
    private function loadCurrentMessages($locale, $transPaths, TranslationLoader $loader)
    {
       $currentCatalogue = new MessageCatalogue($locale);
       foreach ($transPaths as $path) {
           $path = $path.'translations';
           if (is_dir($path)) {
               $loader->loadMessages($path, $currentCatalogue);
           }
       }
       return $currentCatalogue;
    }
    /**
    * @param string            $locale
    * @param array             $transPaths
    * @param TranslationLoader $loader
    *
    * @return MessageCatalogue[]
    */
    private function loadFallbackCatalogues($locale, $transPaths, TranslationLoader $loader)
    {
       $fallbackCatalogues = array();
       $translator = $this->_container->get('translator');
       if ($translator instanceof Translator) {
           foreach ($translator->getFallbackLocales() as $fallbackLocale) {
               if ($fallbackLocale === $locale) {
                   continue;
               }
               $fallbackCatalogue = new MessageCatalogue($fallbackLocale);
               foreach ($transPaths as $path) {
                   $path = $path.'translations';
                   if (is_dir($path)) {
                       $loader->loadMessages($path, $fallbackCatalogue);
                   }
               }
               $fallbackCatalogues[] = $fallbackCatalogue;
           }
       }
       return $fallbackCatalogues;
    }
}
