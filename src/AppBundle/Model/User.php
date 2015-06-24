<?php

namespace AppBundle\Model;

class User
{
	protected $id, $name, $email;

	function __construct($id, $name, $email)
	{
		$this->id    = $id;
		$this->name  = $name;
		$this->email = $email;
	}

	public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

}