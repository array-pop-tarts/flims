<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ScreeningsTable extends Table
{
  public function initialize(array $config) {
    
    $this->belongsTo('Films');
    $this->belongsTo('Locations');
    $this->belongsToMany('Viewers');
    
    $this->addBehavior('Timestamp');
  }
  
  public function validationDefault(Validator $validator) {
    $validator
      ->notEmpty('screened', 'Enter a date');
    
    return $validator;
  }
  
}