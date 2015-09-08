<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Event\Event;
use Cake\ORM\Query;

class ViewersTable extends Table
{
  public function initialize(array $config) {
    
    $this->belongsToMany('Screenings')
      ->dependent(true);
    
    $this->addBehavior('Timestamp');
  }
  
  public function beforeFind(Event $event, Query $query, \ArrayObject $options, $primary) {
    $order = $query->clause('order');
    if ($order === null || !count($order)) {
      $query->order(['Viewers.name']);
    }
  }
  
  public function addOnTheFly($name) {
    $newViewer['name'] = $name;
    $viewer = $this->newEntity();
    $this->patchEntity($viewer, $newViewer);
    $savedViewer = $this->save($viewer);
    return $savedViewer->id;
  }
  
}