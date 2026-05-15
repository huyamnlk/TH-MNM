<?php
class ProductModel
{
// Thuộc tính của lớp ProductModel
    private $ID;
    private $Name;
    private $Description;
    private $Price;
    private $Image; // Thêm thuộc tính Image
    private $CategoryID; // Thêm thuộc tính CategoryID

    // Constructor để khởi tạo đối tượng ProductModel
    public function __construct($ID, $Name, $Description, $Price, $Image = '', $CategoryID = null)
    {
        $this->ID = $ID;
        $this->Name = $Name;
        $this->Description = $Description;
        $this->Price = $Price;
        $this->Image = $Image;
        $this->CategoryID = $CategoryID;
    }
// Getter và Setter cho thuộc tính ID
public function getID()
{
return $this->ID;
}
public function setID($ID)
{
$this->ID = $ID;
}
// Getter và Setter cho thuộc tính Name
public function getName()
{
return $this->Name;
}
public function setName($Name)
{
$this->Name = $Name;
}
// Getter và Setter cho thuộc tính Description
public function getDescription()
{
return $this->Description;
}
public function setDescription($Description)
{
$this->Description = $Description;
}
// Getter và Setter cho thuộc tính Price
    public function getPrice()
    {
        return $this->Price;
    }

    public function setPrice($Price)
    {
        $this->Price = $Price;
    }

    // Getter và Setter cho thuộc tính Image
    public function getImage()
    {
        return $this->Image;
    }

    public function setImage($Image)
    {
        $this->Image = $Image;
    }

    // Getter và Setter cho thuộc tính CategoryID
    public function getCategoryID()
    {
        return $this->CategoryID;
    }

    public function setCategoryID($CategoryID)
    {
        $this->CategoryID = $CategoryID;
    }
}
?>