<?php

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Table;

class InsertableBehavior extends Behavior {
  
  public function addOnTheFly($name) {
    $new['name'] = $name;
    $entity = $this->_table->newEntity();
    $this->_table->patchEntity($entity, $new);
    $saved = $this->_table->save($entity);
    return $saved->id;
  }
  
}