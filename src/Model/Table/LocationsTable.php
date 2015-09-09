<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\Validation\Validator;

class LocationsTable extends Table
{
  public function initialize(array $config) {
    
    $this->hasMany('Screenings');
    
    $this->addBehavior('Timestamp');
    $this->addBehavior('Insertable');
  }
  
  public function validationDefault(Validator $validator) {
    $validator
      ->add('name', ['unique' => ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'Name already exists']]);
    
    return $validator;
  }
  
  public function beforeFind(Event $event, Query $query, \ArrayObject $options, $primary) {
    $order = $query->clause('order');
    if ($order === null || !count($order)) {
      $query->order(['Locations.name']);
    }
  }
  
}