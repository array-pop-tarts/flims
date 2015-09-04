<?php

namespace App\Controller;

class FilmsController extends AppController
{
    public $paginate = [
      'sortWhiteList' => ['title', 'year', 'Screenings.screened']
    ];
    
    public function index() {
      
      $films = $this->Films->find('all')
        ->contain(['Translations', 'Screenings' => ['Locations', 'Viewers']]);
        
      $count = $films->count();
        
      $films = $this->paginate($films);
      $this->set(compact('films', 'count'));
    }
    
    public function add() {
      $film = $this->Films->newEntity();
      $this->set(compact('film'));
      $this->_form($film);
    }
    
    public function edit($id) {
      $film = $this->Films->find('all', [
        'contain' => [
          'Translations',
          'Screenings' => ['Viewers']
        ],
        'conditions' => ['Films.id' => $id]
      ]);
      $film = $film->first();
      $this->set(compact('film'));
      $this->_form($film);
    }
    
    private function _form($film) {
      
      if ($this->request->is(['post', 'put'])) {
        //pr($this->request->data);
        $this->Films->patchEntity($film, $this->request->data, ['associated' => ['Translations', 'Screenings.Viewers']]);
        if ($this->Films->save($film)) {
          $this->Flash->success('Film saved.');
          return $this->redirect(['action' => 'index']);
        }
        else
          $this->Flash->error('Film was not saved.');
      }
      
      $locations = $this->Films->Screenings->Locations->find('list');
      $locations = $locations->toArray();
      $viewers = $this->Films->Screenings->Viewers->find('list');
      $viewers = $viewers->toArray();
      
      $years = array_fill(0, 10, 2015);
      
      $this->set(compact('locations', 'viewers'));
      $this->render('_form');
    }
}
