<?php

namespace App\Service;

use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LogService
{
    protected $entityManager;

    private $settingService;

    public function __construct(EntityManagerInterface $entityManager, SettingService $settingService, ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->settingService = $settingService;
    }

    public function createLog(Log $log)
    {
        $this->entityManager->persist($log);

        $this->entityManager->flush();
    }

    /**
     * @return string
     */
    public function findLogsByCreatedTime($createdTime)
    {
        return $this->getLogEntity()->findAllByConditions(array('createdTime' => $createdTime));
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    private function getLogEntity()
    {
        return $this->entityManager->getRepository(Log::class);
    }
}