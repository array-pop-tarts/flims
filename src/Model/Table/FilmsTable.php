<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Event\Event;
use Cake\ORM\Query;

class FilmsTable extends Table
{
  public function initialize(array $config) {
    
    $this->hasMany('Acquisitions');
    $this->hasMany('Screenings');
    $this->hasOne('Translations');
    
    $this->addBehavior('Timestamp');
  }
  
  public function beforeFind(Event $event, Query $query, \ArrayObject $options, $primary) {
    $order = $query->clause('order');
    if ($order === null || !count($order)) {
      $query->order(['Films.released DESC', 'Films.title']);
    }
  }
}