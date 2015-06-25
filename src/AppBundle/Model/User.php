<?php

namespace AppBundle\Model;

class User
{
	protected $id, $name, $email;

	public function __construct($id = null, $name = null, $email = null)
	{
		$this->id    = $id;
		$this->name  = $name;
		$this->email = $email;
	}

    public static function generateId()
    {
        return uniqid();
    }

	public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

}