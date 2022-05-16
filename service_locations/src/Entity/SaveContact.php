<?php

namespace Concrete\Package\ServiceLocations\Src\Entity;

use Core;
use Concrete\Core\Package\PackageService;
use Database;
/**
 * @Entity
 * @Table(name="savecontact")
 */


class SaveContact

{

  /**
   * @Id
   * @Column(name="id", type="integer", options={"unsigned"=true})
   * @GeneratedValue(strategy="AUTO")
   */
  protected $id;

    /**
     * @Column(type="string")
     */
    protected $name;

	/**
    * @Column(type="string")
    */
    protected $email;
    

  /** 
  * @Column(type="integer")
  */
    protected $phone;

  /**
  * @Column(type="string")
  */
    protected $country;

  /**
   * @param mixed $name
   * @return savecontact
   */
  public function setName($name)
  {
      $this->name = $name;
      return $this;
  }

 /**
  * @param mixed $email
  * @return savecontact
  */
    
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
    * @param mixed $phone
    * @return savecontact
    */
    
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }
    
    /**
    * @param mixed $country
    * @return savecontact
    */
    
    public function setCountry($country)
    {
        $this->country = $country;
    }
    
    
    
    

 
  
  public function getName()
  {
      return $this->name;
  }
   
  public function getEmail()
  {
      return $this->email;
  }
    
  public function getPhone()
  {
      return $this->phone;
  }
  
  public function getCountry()
  {
      return $this->country;
  }
    
    
    
    
 public function getEntryID()
 {
      return $this->id;
  }
  public function AddData($data)
  {
      $em = \ORM::entityManager();
		$typeEntry = new self();
		$typeEntry = $this->setupForm($typeEntry,$data);
		
		
      $em->persist($typeEntry);
      $em->flush();
      return $typeEntry;
  }

  public function getTypeByID($id)
  {
      $pkg = \Core::make(PackageService::class)->getByHandle('service_locations');
      $em = $pkg->getEntityManager();
      $typeEntry = $em->getRepository('Concrete\Package\ServiceLocations\Src\Entity\SaveContact')->find($id);
      return $typeEntry;
  }


    
public function setupForm($typeEntry,$data){
	
		$typeEntry->setName(trim($data['name']));
		$typeEntry->setEmail(trim($data['email']));
        $typeEntry->setPhone(trim($data['phone']));
        $typeEntry->setCountry(trim($data['country']));
		return $typeEntry;		
}

  

  
  
}
