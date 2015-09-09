<?php

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Table;

class InsertableBehavior extends Behavior {
  
  public function addOnTheFly($name) {
    
    $existing = $this->_table->findByName($name)->first();
    if (empty($existing)) {
      $data['name'] = $name;
      $entity = $this->_table->newEntity();
      $this->_table->patchEntity($entity, $data);
      $saved = $this->_table->save($entity);
      return $saved->id;
    }
    else
      return $existing->id;
  }
  
}