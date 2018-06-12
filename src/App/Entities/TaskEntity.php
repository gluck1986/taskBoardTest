<?php
/**
 * Created by PhpStorm.
 * User: gluck
 * Date: 10.06.2018
 * Time: 16:45
 */
declare(strict_types=1);

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TaskEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $userName;
    /**
     * @ORM\Column(type="string")
     */
    private $email;
    /**
     * @ORM\Column(type="text")
     */
    private $description;
    /**
     * @ORM\Column(type="string")
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     */
    private $resolved;

    /**
     * TaskEntity constructor.
     * @param $id
     * @param string $userName
     * @param string $email
     * @param string $description
     * @param string $image
     * @param bool $resolved
     */
    public function __construct(
        $id,
        string $userName,
        string $email,
        string $description,
        string $image = '',
        bool $resolved = false
    ) {
        $this->id = $id;
        $this->userName = $userName;
        $this->email = $email;
        $this->description = $description;
        $this->image = $image;
        $this->resolved = $resolved;
    }

    /**
     * @return mixed
     */
    public function getResolved()
    {
        return $this->resolved;
    }

    /**
     * @param mixed $resolved
     */
    public function setResolved($resolved)
    {
        $this->resolved = $resolved;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName(string $userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image)
    {
        $this->image = $image;
    }
}
