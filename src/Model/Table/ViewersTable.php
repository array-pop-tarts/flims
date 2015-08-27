<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class ViewersTable extends Table
{
  public function initialize(array $config) {
    
    $this->belongsToMany('Screenings');
    
  }
}