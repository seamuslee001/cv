<?php
namespace Civi\Cv\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class BootCommand extends BaseCommand {

  protected function configure() {
    $this
      ->setName('php:boot')
      ->setDescription('Generate PHP bootstrap code')
      ->addOption('level', NULL, InputOption::VALUE_REQUIRED, 'Bootstrap level (classloader,civi,full)', 'full');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->boot($input, $output);

    switch ($input->getOption('level')) {
      case 'classloader':
        $code = sprintf('require_once  %s . "/CRM/Core/ClassLoader.php";', var_export(rtrim($GLOBALS["civicrm_root"], '/'), 1))
          . '\CRM_Core_ClassLoader::singleton()->register();';
        break;

      case 'civi':
        $code = \Civi\Cv\Bootstrap::singleton()->generate()
          . '\CRM_Core_Config::singleton();';
        break;

      case 'full':
        $code = \Civi\Cv\Bootstrap::singleton()->generate()
          . '\CRM_Core_Config::singleton();'
          . '\CRM_Utils_System::loadBootStrap(array(), FALSE);';
        break;

      default:
        break;
    }

    $output->writeln($code);
  }

}