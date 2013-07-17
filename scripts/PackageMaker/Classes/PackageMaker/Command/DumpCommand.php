<?php

namespace PackageMaker\Command;

use PackageMaker\Util\ExtensionUtility;
use Symfony\Component\Console as Console;

class DumpCommand extends AbstractCommand {

	/**
	 * @var \PackageMaker\Util\ExtensionUtility
	 */
	protected $extensionUtility;

	/**
	 * @param string $name The name of the command
	 */
	public function __construct($name = null) {
		parent::__construct($name);

		$this->extensionUtility = new ExtensionUtility();

		$this->setDescription('Dump Database');
		$this->setHelp('Optimize database and then dump into `introduction.sql` for the bootstrap package..');
		$this->addOption('dry-run', 'd', Console\Input\InputOption::VALUE_NONE, 'Output command that are going to be executed but don\'t run them.');
		$this->addOption('move', 'm', Console\Input\InputOption::VALUE_NONE, 'Move the dump file into EXT:introduction instead of letting the file where it was created (current directory).');
	}

	/**
	 * @param Console\Input\InputInterface $input
	 * @param Console\Output\OutputInterface $output
	 * @return int|null|void
	 */
	protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output) {

		// Get the configuration for this website
		$configurationPath = realpath(__DIR__ . '/../../../../../htdocs/typo3conf/LocalConfiguration.php');
		$alternativeConfigurationFile = realpath(__DIR__ . '/../../../../../scripts/Configuration.php');
		$introductionPackagePath = realpath(__DIR__ . '/../../../../../htdocs/typo3conf/ext/introduction/Resources/Private/Subpackages/Bootstrap/Database/introduction.sql');

		$configuration = require($configurationPath);
		if (file_exists($alternativeConfigurationFile)) {
			require($alternativeConfigurationFile);
			if (!empty($GLOBALS['TYPO3_CONF_VARS']['DB']['database'])) {
				$configuration['DB']['database'] = $GLOBALS['TYPO3_CONF_VARS']['DB']['database'];
			}
			if (!empty($GLOBALS['TYPO3_CONF_VARS']['DB']['host'])) {
				$configuration['DB']['host'] = $GLOBALS['TYPO3_CONF_VARS']['DB']['host'];
			}
			if (!empty($GLOBALS['TYPO3_CONF_VARS']['DB']['password'])) {
				$configuration['DB']['password'] = $GLOBALS['TYPO3_CONF_VARS']['DB']['password'];
			}
			if (!empty($GLOBALS['TYPO3_CONF_VARS']['DB']['username'])) {
				$configuration['DB']['username'] = $GLOBALS['TYPO3_CONF_VARS']['DB']['username'];
			}
		}
		if (empty($configuration['DB']['database']) || empty($configuration['DB']['username']) || empty($configuration['DB']['password'])) {
			die('No database credentials found. Are there somewhere else than in typo3conf/LocalConfiguration.php?');
		}

		$commands[] = 'echo "Cleaning up database..."';
		$tables = array(
			'be_sessions',
			'cache_imagesizes',
			'sys_log',
			'sys_lockedrecords',
			'sys_history',
			'sys_registry',
			'sys_file_processedfile',
			'sys_refindex',
			'tx_extensionmanager_domain_model_extension',
			'tx_rsaauth_keys',
			'tx_ncstaticfilecache_file',
		);

		// Get a list of cache table which should be cleared beforehand and merge them into the $tables variable.
		$tablePrefixes = array(
			'cf_',
			'index_',
			'tx_realurl_',
		);
		foreach ($tablePrefixes as $prefix) {

			$request = sprintf("SELECT GROUP_CONCAT(DISTINCT table_name) FROM information_schema.tables WHERE table_schema = '%s' AND table_name like '%s%%';",
				$configuration['DB']['database'],
				$prefix
			);
			$command = sprintf('mysql -u root -p%s -e "%s"',
				$configuration['DB']['password'],
				$request
			);

			// get the result
			$result = $this->work($command);
			if (!empty($result[1])) {
				$tables = array_merge($tables, explode(',', $result[1]));
			}
		}

		foreach ($tables as $table) {
			$commands[] = sprintf('mysql -u root -p%s -e "TRUNCATE table %s;" %s',
				$configuration['DB']['password'],
				$table,
				$configuration['DB']['database']
			);
		}

		// Remove record with flag delete = 0
		$tables = array(
			'tt_content',
			'pages',
			'sys_file',
			'sys_file_reference',
		);

		foreach ($tables as $table) {
			$commands[] = sprintf('mysql -u root -p%s -e "DELETE FROM %s WHERE deleted=1;" %s',
				$configuration['DB']['password'],
				$table,
				$configuration['DB']['database']
			);
		}

		$commands[] = 'echo "Dumping database..."';
		$commands[] = sprintf('mysqldump -u %s -p%s --skip-quote-names --skip-extended-insert -c %s | sed "s/AUTO_INCREMENT=[0-9]* //" > introduction.sql',
			$configuration['DB']['username'],
			$configuration['DB']['password'],
			$configuration['DB']['database']
		);

		if ($input->getOption('move')) {
			$commands[] = 'mv introduction.sql ' . $introductionPackagePath;
		}

		if ($input->getOption('dry-run')) {
			$output->writeln($commands);
		} else {
			$this->work($commands);
		}
	}
}