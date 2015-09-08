<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class MediaTable extends Table
{
  public function initialize(array $config) {
    
    $this->belongsToMany('Films')
      ->dependent(true);
  }
}