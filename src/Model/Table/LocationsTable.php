<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Event\Event;
use Cake\ORM\Query;

class LocationsTable extends Table
{
  public function initialize(array $config) {
    
    $this->hasMany('Screenings');
    
    $this->addBehavior('Timestamp');
  }
  
  public function beforeFind(Event $event, Query $query, \ArrayObject $options, $primary) {
    $order = $query->clause('order');
    if ($order === null || !count($order)) {
      $query->order(['Locations.name']);
    }
  }
  
  public function addOnTheFly($name) {
    $newLocation['name'] = $name;
    $location = $this->newEntity();
    $this->patchEntity($location, $newLocation);
    $savedLocation = $this->save($location);
    return $savedLocation->id;
  }
  
}