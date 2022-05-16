<?php

namespace Concrete\Package\ServiceLocations\Src\Entity;

use Core;
use Concrete\Core\Package\PackageService;
use Database;
/**
 * @Entity
 * @Table(name="saveform")
 */


class SaveForm

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
     * @Column(type="integer")
     */
    protected $age;
	
    
    /**
     * @Column(type="string")
     */
    protected $email;
	
	/**
    * @Column(type="string")
    */
    protected $address;
   
	
    /**
    * @Column(type="string")
    */
    protected $designation;
	
    /**
    * @Column(type="string")
    */
    protected $comment;

  

  /**
   * @param mixed $name
   * @return saveform
   */
  public function setName($name)
  {
      $this->name = $name;
      return $this;
  }


  /**
   * @param mixed $age
   * @return saveform
   */
  public function setAge($age)
  {
      $this->age = $age;
      return $this;
  }

  /**
   * @param mixed $email
   * @return saveform
   */
  public function setEmail($email)
  {
      $this->email = $email;
      return $this;
  }
    
    /**
    * @param mixed $address
    * @return saveform
    */
  
  public function setAddress($address)
  {
      $this->address = $address;
      return $this;
  }
   
   
    /**
    * @param mixed $designation
    * @return saveform
    */
    
    public function setDesignation($designation)
    {
        $this->designation =$designation;
        return $this;
    }
    
    
    /**
    * @param mixed $comment
    * @return saveform
    */
    
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

 
  
  public function getName()
  {
      return $this->name;
  }
  public function getAge()
  {
      return $this->age;
  }


  public function getEmail()
  {
      return $this->email;
  } 
    
  public function getAddress()
  {
      return $this->address;
  }
  
 public function getDesignation()
 {
     return $this->designation;
 }
    
 public function getComment()
 {
     return $this->comment;
 }
    
 public function getEntryID()
 {
      return $this->id;
  }
  public function AddEntry($data)
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
      $typeEntry = $em->getRepository('Concrete\Package\ServiceLocations\Src\Entity\SaveForm')->find($id);
      return $typeEntry;
  }
  
//  /**
//   * Modifies an existing entry
//   *
//   * @param AuZipcodes $types
//   * @param string $messageTxt
//   */
//  public function updateEntry(AuZipcodes $types, $data)
//  {
//    $em = \ORM::entityManager();
//    $typeEntry = $types;
//	
//		$typeEntry = $this->setupForm($typeEntry,$data);
//		$em->persist($typeEntry);
//    $em->flush();
//    return $typeEntry;
//
//  }


    
public function setupForm($typeEntry,$data){
	
		$typeEntry->setName(trim($data['name']));
		$typeEntry->setAge(trim($data['age']));
		$typeEntry->setEmail($data['email']);
        $typeEntry->setAddress($data['address']);
        $typeEntry->setDesignation($data['designation']);
        $typeEntry->setComment($data['comment']);
        //$typeEntry->setComment($data['comment']);
		return $typeEntry;		
}

  
   /**
   * Delete an existing entry
   *
   * @param SaveForm $types
   * @param string $messageTxt
   */
  public function remove(SaveForm $types){

      $em = \ORM::entityManager();
      $em->remove($types);
      $em->flush();

  }
 
  
  
}
