<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\Validation\Validator;

class FilmsTable extends Table
{
  public function initialize(array $config) {
    
    $this->belongsToMany('Media')
      ->targetForeignKey('medium_id');
    $this->hasMany('Screenings')
      ->dependent(true);
    $this->hasOne('Translations')
      ->dependent(true);
    
    $this->addBehavior('Timestamp');
  }
  
  public function validationDefault(Validator $validator) {
    $validator
      ->notEmpty('title', 'Enter the film\'s title in its original language');
    
    return $validator;
  }
  
  public function beforeFind(Event $event, Query $query, \ArrayObject $options, $primary) {
    $order = $query->clause('order');
    if ($order === null || !count($order)) {
      $query->order(['Films.released DESC', 'Films.title']);
    }
  }
  
  public function years() {
    $years = array();
    for ($year = date('Y'); $year >= 1900; $year--)
      $years[$year] = $year;
    return $years;
  }
}