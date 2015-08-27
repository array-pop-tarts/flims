<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TranslationsTable extends Table
{
  public function initialize(array $config) {
    
    $this->belongsTo('Films');
    
  }
}