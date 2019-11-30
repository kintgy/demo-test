<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LogRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Log
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=16, options={"comment": "日志等级"})
     */
    private $level;

    /**
     * @ORM\Column(type="string", length=32, options={"comment": "日志所属模块"})
     */
    private $module;

    /**
     * @ORM\Column(type="string", length=64, options={"comment": "日志所属操作类型"})
     */
    private $action;

    /**
     * @ORM\Column(type="text", options={"comment": "日志内容"})
     */
    private $message;

    /**
     * @ORM\Column(type="text", nullable=true, options={"comment": "日志数据"})
     */
    private $data;

    /**
     * @ORM\Column(type="bigint", options={"comment": "日志发生时间"})
     */
    private $createdTime;

    /**
     * @ORM\PrePersist()
     */
    public function onPrePersist()
    {
        $this->createdTime = time();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level): void
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param mixed $module
     */
    public function setModule($module): void
    {
        $this->module = $module;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action): void
    {
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * @param mixed $createdTime
     */
    public function setCreatedTime($createdTime): void
    {
        $this->createdTime = $createdTime;
    }
}
