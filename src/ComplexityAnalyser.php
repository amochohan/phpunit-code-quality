<?php

namespace DrawMyAttention\CodeQuality;

use Exception;
use Symfony\Component\Console\Application;
use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ComplexityAnalyser
 *
 * Analyse the complexity of changed code.
 */
class ComplexityAnalyser extends Application
{
    /**
     * @var string Application version number.
     */
    private $version = '1.0.0';

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var string Root directory of the application.
     */
    private $projectRootDirectory;

    /**
     * @var array Directories which should be included in analysis.
     */
    private $directoriesToScan = [];

    /**
     * @var array Directories which should not be included in analysis.
     */
    private $excludedDirectories = [];

    /**
     * @var array Files which should not be analysed.
     */
    private $excludedFiles = [];

    /**
     * @var string XML rules file used by PHP mess-detector.
     */
    private $phpMdRulesFile;

    /**
     * @var string Fully qualified path to the phpmd executable.
     */
    private $phpMdExecutablePath;

    /**
     * ComplexityAnalyser constructor.
     *
     * @param null|string $configurationFile
     * @param null|string $phpMdRules PHP Mess detector rules file
     */
    public function __construct($configurationFile = null, $phpMdRules = null)
    {
        $this->loadConfiguration($configurationFile, $phpMdRules);

        parent::__construct('Code complexity analyser', $this->version);
    }

    /**
     * Load the application configuration.
     * @param string|null $configurationFile
     * @param string|null $phpMdRules
     */
    private function loadConfiguration($configurationFile = null, $phpMdRules = null)
    {
        if (is_null($configurationFile)) {
            $configurationFile = 'complexity-analyser-config.php';
            $userDefinedConfigFile = getcwd() . $configurationFile;
            if (file_exists($userDefinedConfigFile)) {
               $configurationFile = $userDefinedConfigFile;
            }
        }

        $config = require_once($configurationFile);

        $this->projectRootDirectory = getcwd();

        $this->directoriesToScan = $config['scan_directories'];

        $this->excludedDirectories = $config['excluded_directories'];

        $this->excludedFiles = $config['excluded_files'];

        $this->phpMdExecutablePath = getcwd() . '/vendor/bin/phpmd';

        if (is_null($phpMdRules)) {
            $rulesFile = 'phpmd-rules.xml';
            $userDefinedRulesFile = getcwd() . DIRECTORY_SEPARATOR . $rulesFile;
            if (file_exists($rulesFile)) {
                $phpMdRules = $userDefinedRulesFile;
            } else {
                $phpMdRules = __DIR__ . '/phpmd-rules.xml';
            }
        }

        $this->phpMdRulesFile = $phpMdRules;
    }

    /**
     * Runs the current application.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws Exception
     * @return int 0 if everything went fine, or an error code
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->input  = $input;

        $this->output = $output;

        $this->output->writeln('');
        $this->output->writeln('Analysing code complexity.');

        $files = $this->filesChangedInProject();

        $this->output->writeln('Files changed: ' . count($files));

        if (!$this->checkPhpMd($files)) {
            $this->error('Complexity issues found!');
            exit();
        }

        $this->write('Code quality passed!', 'white', 'green');
    }

    /**
     * Write a message to the console.
     *
     * @param string $message
     * @param string $foreground
     * @param string $background
     * @param string $options
     */
    private function write($message, $foreground = 'white', $background = 'black', $options = 'bold')
    {
        $this->output->writeln('<fg='.$foreground.';options='.$options.';bg='.$background.'>'.
            $message
            .'</fg='.$foreground.';options='.$options.';bg='.$background.'>');
    }

    /**
     * Write an error message to the screen.
     *
     * @param string $message
     */
    private function error($message)
    {
        $this->write('<error>'.$message.'</error>', 'white', 'red');
    }

    /**
     * Get the PHP files which have changed in the current project.
     *
     * @return array
     */
    private function filesChangedInProject()
    {
        $changedPhpFiles = $this->changedFiles();

        return array_filter($changedPhpFiles, function ($changedFile) {
            return $this->isDirectoryScannable($changedFile) &&
                ! $this->isDirectoryExcluded($changedFile) &&
                ! $this->isFileExcluded($changedFile);

        });
    }

    /**
     * Is the file excluded from analysis?
     *
     * @param string $file
     * @return bool
     */
    private function isFileExcluded($file)
    {
        return in_array($file, $this->excludedFiles);
    }

    /**
     * Is the directory excluded from analysis?
     *
     * @param string $directory
     * @return bool
     */
    private function isDirectoryExcluded($directory)
    {
        $fileDirectory = pathinfo($directory)['dirname'];

        $parentDirectories = explode(DIRECTORY_SEPARATOR, $fileDirectory);

        foreach ($parentDirectories as $key => $directory) {
            if ($key > 0) {
                $directory = $parentDirectories[$key - 1] . DIRECTORY_SEPARATOR . $directory;
            }

            if (in_array($directory, $this->excludedDirectories)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Is the directory ok to scan?
     *
     * @param string $directory
     * @return bool
     */
    private function isDirectoryScannable($directory)
    {
        $fileDirectory = pathinfo($directory)['dirname'];

        $parentDirectories = explode(DIRECTORY_SEPARATOR, $fileDirectory);

        foreach ($parentDirectories as $key => $directory) {
            if ($key > 0) {
                $directory = $parentDirectories[$key - 1] . DIRECTORY_SEPARATOR . $directory;
            }

            if (in_array($directory, $this->directoriesToScan)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the PHP files which have changed since the last commit.
     *
     * @return array
     */
    private function changedFiles()
    {
        $changedFiles = [];

        exec("git diff --name-only", $changedFiles);

        return array_filter($changedFiles, function ($changedFile) {
            return pathinfo($changedFile)['extension'] === 'php';
        });
    }

    /**
     * @param $files
     *
     * @return bool
     */
    private function checkPhpMd($files)
    {
        $success = true;

        foreach ($files as $file) {
            $processBuilder = new ProcessBuilder([
                $this->phpMdExecutablePath,
                $file,
                'text',
                $this->phpMdRulesFile
            ]);

            $processBuilder->setWorkingDirectory($this->projectRootDirectory);
            $process = $processBuilder->getProcess();
            $process->run();
            if (!$process->isSuccessful()) {
//                dd($process->getExitCodeText(), $process->getExitCode(), $process->getErrorOutput(), $process->getCommandLine(), $process->getOutput());
                $output = preg_replace('!\s+!', ' ', $process->getOutput());
                $this->write(trim($output));
                $this->write(trim($file));
                $this->write(trim($process->getErrorOutput()));
                $success = false;
            }
        }
        return $success;
    }
}