<?php

namespace App\Service;

use App\Entity\Setting;
use Doctrine\ORM\EntityManagerInterface;

class SettingService
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $name
     * @param null $default
     * @return null|object
     */
    public function getByName(string $name, $default = null)
    {
        $setting = $this->entityManager->getRepository(Setting::class)->findOneBy(['name' => $name]);
        return empty($setting) ? $default : $setting;
    }

    public function setByName(string $name, Setting $setting)
    {
        $originSetting = $this->getByName($name);

        if ($originSetting) {
            $this->entityManager->remove($originSetting);
        }

        $this->entityManager->persist($setting);
        $this->entityManager->flush();

        return $this->getByName($name);
    }
}