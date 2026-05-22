<?php
class CategoryModel
{
    private $ID;
    private $Name;
    private $Description;

    public function __construct($ID, $Name, $Description)
    {
        $this->ID = $ID;
        $this->Name = $Name;
        $this->Description = $Description;
    }

    public function getID()
    {
        return $this->ID;
    }

    public function setID($ID)
    {
        $this->ID = $ID;
    }

    public function getName()
    {
        return $this->Name;
    }

    public function setName($Name)
    {
        $this->Name = $Name;
    }

    public function getDescription()
    {
        return $this->Description;
    }

    public function setDescription($Description)
    {
        $this->Description = $Description;
    }
}
?>

