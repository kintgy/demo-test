<?php

namespace App\Command;

use App\Entity\Log;
use App\Entity\Setting;
use App\Service\LogService;
use App\Service\SettingService;
use App\UploadStorage\UploadStorageFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UploadLogCommand extends Command
{
    protected static $defaultName = 'log:upload-storage';

    private $uploadFactory;

    private $settingService;

    private $logService;

    private $container;

    public function __construct(UploadStorageFactory $factory, SettingService $setting, LogService $log, ContainerInterface $container, $name = null)
    {
        parent::__construct($name);
        $this->uploadFactory = $factory;
        $this->settingService = $setting;
        $this->logService = $log;
        $this->container = $container;
    }

    protected function configure()
    {
        $this->setDescription('上传日志文件至目标存储')
            ->setHelp('上传日志文件至目标存储')
            ->addArgument('supplier', InputArgument::REQUIRED, '云存储供应商');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $currentTime = time();

        $this->mockData($output);
        $lastUploadTimeSetting = $this->settingService->getByName('last_upload_time', 0);

        $lastUploadTime = $lastUploadTimeSetting ? $lastUploadTimeSetting->getValue() : $lastUploadTimeSetting;

        $output->writeln('开始整理整理日志文件');
        $fileName = date('Y-m-d_H:i:s', $lastUploadTime) . '.log';
        $filePath = $this->container->getParameter('log_storage_path') . DIRECTORY_SEPARATOR . $fileName;
        $fileContents = $this->formatLogs($this->logService->findLogsByCreatedTime($lastUploadTimeSetting));

        file_put_contents($filePath, $fileContents);
        $output->writeln('日志文件整理完毕');

        $output->writeln('开始上传文件' . $fileName);

        $uploadStorage = $this->uploadFactory->create($input->getArgument('supplier'));
        $uploadStorage->upload($filePath, $fileName);

        $output->writeln('上传文件' . $fileName . '完毕');

        $this->updateUploadTime($currentTime);

        return true;
    }

    private function updateUploadTime($time)
    {
        $setting = new Setting();
        $setting->setName('last_upload_time');
        $setting->setValue($time);
        $this->settingService->setByName($setting->getName(), $setting);
    }

    private function formatLogs($logs)
    {
        $contents = '';
        foreach ($logs as $log) {
            $contents .= '';
            $contents .= '[' . date('Y-m-d H:i:s', $log->getCreatedTime()) . ']';
            $contents .= '-' . strtoupper($log->getLevel()) . '-';
            $contents .= '-' . $log->getModule() . '-';
            $contents .= '-' . $log->getAction() . '-';
            $contents .= '-' . $log->getMessage() . $log->getData() . PHP_EOL;
        }

        return $contents;
    }

    private function mockData(OutputInterface $output)
    {
        $i = 0;
        while ($i < 30) {
            $log = new Log();
            switch ($i) {
                case ($i < 10):
                    $log->setLevel('ERROR');
                case  ($i > 9 && $i < 20):
                    $log->setLevel('WARNING');
                default:
                    $log->setLevel('INFO');
                    break;
            }

            $log->setModule('module' . rand(0, 3));
            $log->setAction('action' . rand(0, 3));
            $log->setMessage('test message' . $i);
            $this->logService->createLog($log);
            $i++;

            $output->writeln('创建日志' . $i);
        }


    }

}