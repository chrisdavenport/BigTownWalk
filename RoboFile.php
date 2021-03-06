<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * Download robo.phar from http://robo.li/robo.phar and type in the root of the repo: $ php robo.phar
 * Or do: $ composer update, and afterwards you will be able to execute robo like $ php vendor/bin/robo
 *
 * @package     Joomla.Site
 * @subpackage  RoboFile
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'vendor/autoload.php';

if (!defined('JPATH_BASE'))
{
	define('JPATH_BASE', __DIR__);
}

/**
 * Modern php task runner for Joomla! Browser Automated Tests execution
 *
 * @package  RoboFile
 *
 * @since    1.0
 */
class RoboFile extends \Robo\Tasks
{
	// Load tasks from composer, see composer.json
	use \joomla_projects\robo\loadTasks;
	use \Joomla\Jorobo\Tasks\loadTasks;

	/**
	 * File extension for executables
	 *
	 * @var string
	 */
	private $executableExtension = '';

	/**
	 * Local configuration parameters
	 *
	 * @var array
	 */
	private $configuration = array();

	/**
	 * Path to the local CMS root
	 *
	 * @var string
	 */
	private $cmsPath = '';

	/**
	 * @var array | null
	 * @since  version
	 */
	private $suiteConfig;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->configuration = $this->getConfiguration();

		$this->cmsPath = $this->getCmsPath();

		$this->executableExtension = $this->getExecutableExtension();

		// Set default timezone (so no warnings are generated if it is not set)
		date_default_timezone_set('UTC');
	}

	/**
	 * Get the executable extension according to Operating System
	 *
	 * @return void
	 */
	private function getExecutableExtension()
	{
		if ($this->isWindows())
		{
			// Check wehter git.exe or git as command should be used,
			// As on window both is possible
			if (!$this->_exec('git.exe --version')->getMessage())
			{
				return '';
			}
			else
			{
				return '.exe';
			}
		}

		return '';
	}

	/**
	 * Executes all the Selenium System Tests in a suite on your machine
	 *
	 * @param   array  $opts  Array of configuration options:
	 *          - 'use-htaccess': renames and enable embedded Joomla .htaccess file
	 *          - 'env': set a specific environment to get configuration from
	 *
	 * @return mixed
	 */
	public function runTests($opts = ['use-htaccess' => false, 'env' => 'desktop'])
	{
		$this->createTestingSite($opts['use-htaccess']);

		$this->getComposer();

		$this->taskComposerInstall()->run();

		$this->runSelenium();

		// Make sure to run the build command to generate AcceptanceTester
		$this->_exec($this->isWindows() ? 'vendor\bin\codecept.bat build' : 'php vendor/bin/codecept build');

		$this->taskCodecept()
			->arg('--steps')
			->arg('--debug')
			->arg('--fail-fast')
			->env($opts['env'])
			->arg('tests/acceptance/install/')
			->run()
			->stopOnFail();

		$this->taskCodecept()
			->arg('--steps')
			->arg('--debug')
			->arg('--fail-fast')
			->env($opts['env'])
			->arg('tests/acceptance/administrator/')
			->run()
			->stopOnFail();

		$this->taskCodecept()
			->arg('--steps')
			->arg('--debug')
			->arg('--fail-fast')
			->env($opts['env'])
			->arg('tests/acceptance/frontend/')
			->run()
			->stopOnFail();

		/*
		Uncomment this lines if you need to debug selenium errors
		$seleniumErrors = file_get_contents('selenium.log');
		if ($seleniumErrors) {
			$this->say('Printing Selenium Log files');
			$this->say('------ selenium.log (start) ---------');
			$this->say($seleniumErrors);
			$this->say('------ selenium.log (end) -----------');
		}
		*/
	}

	/**
	 * Executes a specific Selenium System Tests in your machine
	 *
	 * @param   string  $pathToTestFile  Optional name of the test to be run
	 * @param   string  $suite           Optional name of the suite containing the tests, Acceptance by default.
	 *
	 * @return mixed
	 */
	public function runTest($pathToTestFile = null, $suite = 'acceptance')
	{
		$this->runSelenium();

		// Make sure to run the build command to generate AcceptanceTester
		$this->_exec($this->isWindows() ? 'vendor\bin\codecept.bat build' : 'php vendor/bin/codecept build');

		if (!$pathToTestFile)
		{
			$this->say('Available tests in the system:');

			$iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator(
					'tests/' . $suite,
					RecursiveDirectoryIterator::SKIP_DOTS
				),
				RecursiveIteratorIterator::SELF_FIRST
			);

			$tests = array();

			$iterator->rewind();
			$i = 1;

			while ($iterator->valid())
			{
				if (strripos($iterator->getSubPathName(), 'cept.php')
					|| strripos($iterator->getSubPathName(), 'cest.php'))
				{
					$this->say('[' . $i . '] ' . $iterator->getSubPathName());
					$tests[$i] = $iterator->getSubPathName();
					$i++;
				}

				$iterator->next();
			}

			$this->say('');
			$testNumber	= $this->ask('Type the number of the test  in the list that you want to run...');
			$test = $tests[$testNumber];
		}

		$pathToTestFile = 'tests/' . $suite . '/' . $test;

		// Loading the class to display the methods in the class
		require 'tests/' . $suite . '/' . $test;

		// Logic to fetch the class name from the file name
		$fileName = explode("/", $test);
		$className = explode(".", $fileName[1]);

		// If the selected file is cest only than we will give the option to execute individual methods, we don't need this in cept file
		$i = 1;

		if (strripos($className[0], 'cest'))
		{
			$class_methods = get_class_methods($className[0]);
			$this->say('[' . $i . '] ' . 'All');
			$methods[$i] = 'All';
			$i++;

			foreach ($class_methods as $method_name)
			{
				$reflect = new ReflectionMethod($className[0], $method_name);

				if (!$reflect->isConstructor())
				{
					if ($reflect->isPublic())
					{
						$this->say('[' . $i . '] ' . $method_name);
						$methods[$i] = $method_name;
						$i++;
					}
				}
			}

			$this->say('');
			$methodNumber = $this->ask('Please choose the method in the test that you would want to run...');
			$method = $methods[$methodNumber];
		}

		if (isset($method) && $method != 'All')
		{
			$pathToTestFile = $pathToTestFile . ':' . $method;
		}

		$this->taskCodecept()
			->test($pathToTestFile)
			->arg('--steps')
			->arg('--debug')
			->run()
			->stopOnFail();
	}

	/**
	 * Run the specified checker tool. Valid options are phpmd, phpcs, phpcpd, namespace.
	 *
	 * @param   string  $tool  The tool
	 *
	 * @return  bool
	 */
	public function runChecker($tool = null)
	{
		if ($tool === null)
		{
			$this->say('You have to specify a tool name as argument. Valid tools are phpmd, phpcs, phpcpd, coverage, namespace.');

			return false;
		}

		if (!in_array($tool, array('phpmd', 'phpcs', 'phpcpd', 'coverage', 'namespace')))
		{
			$this->say('The tool you required is not known. Valid tools are phpmd, phpcs, phpcpd, coverage, namespace.');

			return false;
		}

		switch ($tool)
		{
			case 'phpmd':
				return $this->runPhpmd();

			case 'phpcs':
				return $this->runPhpcs();

			case 'phpcpd':
				return $this->runPhpcpd();

			case 'coverage':
				return $this->runCoverage();

			case 'namespace':
				return $this->runNamespace();
		}

		return true;
	}

	/**
	 * Creates a testing Joomla site for running the tests (use it before run:test)
	 *
	 * @param   bool  $use_htaccess  (1/0) Rename and enable embedded Joomla .htaccess file
	 *
	 * @return  bool
	 */
	public function createTestingSite($use_htaccess = false)
	{
		if (!empty($this->configuration->skipClone))
		{
			$this->say('Reusing Joomla CMS site already present at ' . $this->cmsPath);

			return;
		}

		// Caching cloned installations locally
		if (!is_dir('tests/cache') || (time() - filemtime('tests/cache') > 60 * 60 * 24))
		{
			if (file_exists('tests/cache'))
			{
				$this->taskDeleteDir('tests/cache')->run();
			}

			$this->_exec($this->buildGitCloneCommand());
		}

		// Get Joomla Clean Testing sites
		if (is_dir($this->cmsPath))
		{
			try
			{
				$this->taskDeleteDir($this->cmsPath)->run();
			}
			catch (Exception $e)
			{
				// Sorry, we tried :(
				$this->say('Sorry, you will have to delete ' . $this->cmsPath . ' manually. ');
				exit(1);
			}
		}

		$this->_copyDir('tests/cache', $this->cmsPath);

		// Optionally change owner to fix permissions issues
		if (!empty($this->configuration->localUser) && !$this->isWindows())
		{
			$this->_exec('chown -R ' . $this->configuration->localUser . ' ' . $this->cmsPath);
		}

		// Copy current package
		if (!file_exists('dist/pkg-bigtownwalk-current.zip'))
		{
			$this->build(true);
		}

		$this->_copy('dist/pkg-bigtownwalk-current.zip', $this->cmsPath . "/pkg-bigtownwalk-current.zip");

		$this->say('Joomla CMS site created at ' . $this->cmsPath);

		// Optionally uses Joomla default htaccess file. Used by TravisCI
		if ($use_htaccess == true)
		{
			$this->_copy('./tests/joomla/htaccess.txt', './tests/joomla/.htaccess');
			$this->_exec('sed -e "s,# RewriteBase /,RewriteBase /tests/joomla/,g" -in-place tests/joomla/.htaccess');
		}
	}

	/**
	 * Get (optional) configuration from an external file
	 *
	 * @return \stdClass|null
	 */
	public function getConfiguration()
	{
		$configurationFile = __DIR__ . '/RoboFile.ini';

		if (!file_exists($configurationFile))
		{
			$this->say("No local configuration file");

			return null;
		}

		$configuration = parse_ini_file($configurationFile);

		if ($configuration === false)
		{
			$this->say('Local configuration file is empty or wrong (check is it in correct .ini format');

			return null;
		}

		return json_decode(json_encode($configuration));
	}

	/**
	 * Build correct git clone command according to local configuration and OS
	 *
	 * @return string
	 */
	private function buildGitCloneCommand()
	{
		$branch = empty($this->configuration->branch) ? 'staging' : $this->configuration->branch;

		return "git" . $this->executableExtension . " clone -b $branch --single-branch --depth 1 https://github.com/joomla/joomla-cms.git tests/cache";
	}

	/**
	 * Check if local OS is Windows
	 *
	 * @return bool
	 */
	private function isWindows()
	{
		return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
	}

	/**
	 * Get the correct CMS root path
	 *
	 * @return string
	 */
	private function getCmsPath()
	{
		if (empty($this->configuration->cmsPath))
		{
			return 'tests/joomla';
		}

		if (!file_exists(dirname($this->configuration->cmsPath)))
		{
			$this->say("Cms path written in local configuration does not exists or is not readable");

			return 'tests/joomla';
		}

		return $this->configuration->cmsPath;
	}

	/**
	 * Runs Selenium Standalone Server.
	 *
	 * @return void
	 */
	public function runSelenium()
	{
		if (!$this->isWindows())
		{
			$this->_exec("vendor/bin/selenium-server-standalone " . $this->getWebDriver() . ' >> selenium.log 2>&1 &');
		}
		else
		{
			$this->_exec('START java.exe -jar' . $this->getWebDriver() .
				' vendor\joomla-projects\selenium-server-standalone\bin\selenium-server-standalone.jar ');
		}

		if ($this->isWindows())
		{
			sleep(3);
		}
		else
		{
			$this->taskWaitForSeleniumStandaloneServer()
				->run()
				->stopOnFail();
		}
	}

	/**
	 * Downloads Composer
	 *
	 * @return void
	 */
	private function getComposer()
	{
		// Make sure we have Composer
		if (!file_exists('./composer.phar'))
		{
			$insecure = '';

			if (!empty($this->configuration->insecure))
			{
				$insecure = '--insecure';
			}

			$this->_exec('curl ' . $insecure . ' --retry 3 --retry-delay 5 -sS https://getcomposer.org/installer | php');
		}
	}

	/**
	 * Kills the selenium server running
	 *
	 * @param   string  $host  Web host of the remote server.
	 * @param   string  $port  Server port.
	 *
	 * @return  void
	 */
	public function killSelenium($host = 'localhost', $port = '4444')
	{
		$this->say('Trying to kill the selenium server.');
		$this->_exec("curl http://$host:$port/selenium-server/driver/?cmd=shutDownSeleniumServer");
	}

	/**
	 * Run the phpmd tool
	 *
	 * @return  boolean
	 */
	private function runPhpmd()
	{
		$this->_exec('phpmd' . $this->extension . ' ' . __DIR__ . '/src xml cleancode,codesize,controversial,design,naming,unusedcode');

		return true;
	}

	/**
	 * Run the phpcs tool
	 *
	 * @return  boolean
	 */
	private function runPhpcs()
	{
		$this->_exec('phpcs' . $this->extension . ' ' . __DIR__ . '/src');

		return true;
	}

	/**
	 * Run the phpcpd tool
	 *
	 * @return  boolean
	 */
	private function runPhpcpd()
	{
		$this->_exec('phpcpd' . $this->extension . ' ' . __DIR__ . '/src');

		return true;
	}

	/**
	 * Run the PhpUnit code coverage tool.
	 *
	 * @return  boolean
	 */
	public function runCoverage()
	{
		$this->_exec('vendor/bin/phpunit --coverage-html=code_coverage tests/unit');

		return true;
	}

	/**
	 * Run the Joomla namespace checker tool.
	 *
	 * @return  boolean
	 */
	public function runNamespace()
	{
		$this->_exec('php vendor/vortrixs/joomla-namespace-checker/bin/jnsc.phar src');

		return true;
	}

	/**
	 * Build the joomla extension package
	 *
	 * @param   array  $params  Additional params
	 *
	 * @return  void
	 */
	public function build($params = ['dev' => false])
	{
		if (!file_exists('jorobo.ini'))
		{
			$this->_copy('jorobo.dist.ini', 'jorobo.ini');
		}

		// Build the help files from source.
		$this->buildHelp($params);

		// Build the package.
		$this->taskBuild($params)->run();
	}

	/**
	 * Build the help files.
	 *
	 * @param   array  $params  Additional params
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function buildHelp($params)
	{
		// Copy the help CSS file.
		$this->copyHelpCss();

		// Determine the source and target paths.
		$sourcePath = __DIR__ . '/src/docs/help/en-GB';
		$targetPath = __DIR__ . '/src/administrator/components/com_bigtownwalk/help/en-GB';

		// Delete any files from the target help directory.
		$this->deleteFiles($targetPath);

		// Make sure that the target path exists.
		if (!file_exists($targetPath))
		{
			mkdir($targetPath);
		}

		// Get the language strings.
		$strings = $this->loadLanguageStrings();

		// Get all the files in the source directory.
		$files = array_diff(scandir($sourcePath), ['.', '..']);

		foreach ($files as $file)
		{
			$sourceFile = $sourcePath . '/' . $file;
			$targetFile = $targetPath . '/' . $file;

			// Read the file into memory.
			$handle = fopen($sourceFile, 'rb');
			$contents = fread($handle, filesize($sourceFile));
			fclose($handle);

			// If the file contains the string {{EXCLUDE}} then don't copy it to the target area.
			if (stripos($contents, '{{EXCLUDE}}') !== false)
			{
				continue;
			}

			// Look for and handle transclusions.
			$contents = $this->transclude($contents, $sourcePath, $strings);

			$handle = fopen($targetFile, 'wb');
			fwrite($handle, $contents);
			fclose($handle);

			$this->say('Help file copied to ' . $targetFile);
		}
	}

	/**
	 * Copy help CSS file.
	 *
	 * @return  void
	 */
	private function copyHelpCss()
	{
		copy(__DIR__ . '/src/docs/help/help.css', __DIR__ . '/src/administrator/components/com_bigtownwalk/help/help.css');
		$this->say('Help CSS file copied');

		copy(__DIR__ . '/src/docs/help/template.css', __DIR__ . '/src/administrator/components/com_bigtownwalk/help/template.css');
		$this->say('Help template CSS file copied');
	}

	/**
	 * Load language strings.
	 */
	private function loadLanguageStrings()
	{
		$strings = [];
		$language = 'en-GB';

		// Get language strings from the administrator languages directory.
		$strings[] = $this->loadLanguageStringsFromDirectory(__DIR__ . '/src/administrator/language/' . $language);

		// Get language strings from the front-end languages directory.
		$strings[] = $this->loadLanguageStringsFromDirectory(__DIR__ . '/src/language/' . $language);

		// Get language strings from plugins.
		$pluginGroups = [];

		foreach ($pluginGroups as $pluginGroup)
		{
			$strings[] = $this->loadLanguageStringsFromPlugins($pluginGroup, $language);
		}

		return array_merge(...$strings);
	}

	/**
	 * Load language strings from a directory of files.
	 *
	 * @param   string  $directory  Full directory path.
	 *
	 * @return  array of language strings.
	 *
	 * @since   1.0.0
	 */
	private function loadLanguageStringsFromDirectory(string $directory): array
	{
		$strings = [];

		// If the directory does not exist, return empty-handed.
		if (!file_exists($directory))
		{
			return $strings;
		}

		// Get all the files in the administrator language directory.
		$files = array_diff(scandir($directory), ['.', '..']);

		// If there are no language files, return empty-handed.
		if (empty($files))
		{
			return $strings;
		}

		// Load and parse all the files in the directory.
		foreach ($files as $file)
		{
			$strings[] = parse_ini_file($directory . '/' . $file);
		}

		return array_merge(...$strings);
	}

	/**
	 * Load language strings from plugins.
	 *
	 * @param   string  $pluginGroup  Plugin group name.
	 * @param   string  $language     Language code.
	 *
	 * @return  array
	 *
	 * @since   1.0.0
	 */
	private function loadLanguageStringsFromPlugins(string $pluginGroup, string $language): array
	{
		$strings = [];

		// Look for plugin directories.
		$files = array_diff(scandir(__DIR__ . '/src/plugins/' . $pluginGroup), ['.', '..']);

		// Get language strings for the plugins.
		foreach ($files as $file)
		{
			$strings[] = $this->loadLanguageStringsFromDirectory(
				__DIR__ . '/src/plugins/' . $pluginGroup . '/' . $file . '/language/' . $language
			);
		}

		return array_merge(...$strings);
	}

	/**
	 * Delete all files in a directory.
	 *
	 * @param   string  $path  Delete all files in this directory path.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function deleteFiles(string $path)
	{
		// If the directory doesn't exist, do nothing.
		if (!file_exists($path))
		{
			return;
		}

		$files = array_diff(scandir($path), ['.', '..']);

		foreach ($files as $file)
		{
			unlink($path . '/' . $file);
		}
	}

	/**
	 * Looks for and process transclusions.
	 *
	 * These are of the form: {{code}}.
	 *
	 * @param   string  $contents     Content which may contain transclusions.
	 * @param   string  $sourcePath   Path to source files.
	 * @param   array   $langStrings  Array of language strings.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function transclude(string $contents, string $sourcePath, array $langStrings): string
	{
		$matches = [];
		preg_match_all('/{{(.*)}}/U', $contents, $matches);

		// Nothing to transclude.
		if (empty($matches[0]))
		{
			return $contents;
		}

		// Process each transclusion in turn.
		foreach ($matches[1] as $index => $code)
		{
			// Skip exclusion flag.
			if ($code === 'EXCLUDE')
			{
				$contents = str_replace('{{EXCLUDE}}', '', $contents);

				continue;
			}

			// If it's a valid language string, substitute it.
			if (isset($langStrings[$code]))
			{
				$contents = str_replace('{{' . $code . '}}', $langStrings[$code], $contents);

				continue;
			}

			$sourceFile = $sourcePath . '/' . $code;

			if (!file_exists($sourceFile))
			{
				$this->say('Transclusion source file not found: ' . $sourceFile);

				continue;
			}

			// Read the file into memory.
			$handle = fopen($sourceFile, 'rb');
			$source = fread($handle, filesize($sourceFile));
			fclose($handle);

			// Look for and handle transclusion.
			$source = $this->transclude($source, $sourcePath, $langStrings);

			// Merge transclusion into contents.
			$contents = str_replace($matches[0][$index], $source, $contents);
		}

		return $contents;
	}

	/**
	 * Executes all unit tests
	 *
	 * @return  void
	 */
	public function runUnit()
	{
		$this->createTestingSite();
		$this->getComposer();
		$this->taskComposerInstall()->run();

		// Make sure to run the build command to generate AcceptanceTester
		$this->_exec($this->isWindows() ? 'vendor\bin\codecept.bat build' : 'php vendor/bin/codecept build');

		$this->taskCodecept()
			->suite('unit')
			->run()
			->stopOnFail();
	}

	/**
	 * Update copyright headers for this project. (Set the text up in the jorobo.ini)
	 *
	 * @return  void
	 */
	public function headers()
	{
		if (!file_exists('jorobo.ini'))
		{
			$this->_copy('jorobo.dist.ini', 'jorobo.ini');
		}

		(new \Joomla\Jorobo\Tasks\CopyrightHeader)->run();
	}

	/**
	 * Detect the correct driver for selenium
	 *
	 * @return  string the webdriver string to use with selenium
	 *
	 * @since version
	 */
	public function getWebdriver()
	{
		$suiteConfig        = $this->getSuiteConfig();
		$codeceptMainConfig = \Codeception\Configuration::config();
		$browser            = $suiteConfig['modules']['config']['JoomlaBrowser']['browser'];

		if ($browser == 'chrome')
		{
			$driver['type'] = 'webdriver.chrome.driver';
		}
		elseif ($browser == 'firefox')
		{
			$driver['type'] = 'webdriver.gecko.driver';
		}
		elseif ($browser == 'MicrosoftEdge')
		{
			$driver['type'] = 'webdriver.edge.driver';

			// Check if we are using Windows Insider builds
			if ($suiteConfig['modules']['config']['AcceptanceHelper']['MicrosoftEdgeInsiders'])
			{
				$browser = 'MicrosoftEdgeInsiders';
			}
		}
		elseif ($browser == 'internet explorer')
		{
			$driver['type'] = 'webdriver.ie.driver';
		}

		// Check if we have a path for this browser and OS in the codeception settings
		if (isset($codeceptMainConfig['webdrivers'][$browser][$this->getOs()]))
		{
			$driverPath = $codeceptMainConfig['webdrivers'][$browser][$this->getOs()];
		}
		else
		{
			$this->yell(
				print_r($codeceptMainConfig) .
				'No driver for your browser. Check your browser in acceptance.suite.yml and the webDrivers in codeception.yml');

			// We can't do anything without a driver, exit
			exit(1);
		}

		$driver['path'] = $driverPath;

		return '-D' . implode('=', $driver);
	}

	/**
	 * Get the suite configuration
	 *
	 * @param   string  $suite  The suite
	 *
	 * @return array
	 */
	private function getSuiteConfig($suite = 'acceptance')
	{
		if (!$this->suiteConfig)
		{
			$this->suiteConfig = Symfony\Component\Yaml\Yaml::parse(file_get_contents("tests/{$suite}.suite.yml"));
		}

		return $this->suiteConfig;
	}

	/**
	 * Return the os name
	 *
	 * @return string
	 *
	 * @since version
	 */
	private function getOs()
	{
		$os = php_uname('s');

		if (strpos(strtolower($os), 'windows') !== false)
		{
			$os = 'windows';
		}
		// Who have thought that Mac is actually Darwin???
		elseif (strpos(strtolower($os), 'darwin') !== false)
		{
			$os = 'mac';
		}
		else
		{
			$os = 'linux';
		}

		return $os;
	}

	/**
	 * Update Version __DEPLOY_VERSION__ in bigtownwalk. (Set the version up in the jorobo.ini)
	 *
	 * @return  void
	 */
	public function bump()
	{
		(new \Joomla\Jorobo\Tasks\BumpVersion())->run();
	}
}
