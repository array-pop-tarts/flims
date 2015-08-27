<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class LocationsTable extends Table
{
  public function initialize(array $config) {
    
    $this->hasMany('Screenings');
    
  }
}