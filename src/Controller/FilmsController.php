<?php

namespace App\Controller;

class FilmsController extends AppController
{
  public function index() {
    
    $films = $this->Films->find('all')
      ->contain([
        'Translations',
        'Screenings' => [
          'Locations',
          'Viewers'
        ],
        'Media'
      ]);
      
    $count = $films->count();
      
    $films = $this->paginate($films);
    $this->set(compact('films', 'count'));
  }
  
  public function add() {
    
    $film = $this->Films->newEntity();
    $screenings = $this->Films->Screenings->newEntity();
    
    $film['screenings'] = array(
      0 => $screenings
    );
    
    $this->set(compact('film'));
    $this->_form($film);
  }
  
  public function edit($id) {
    $film = $this->Films->find('all', [
      'contain' => [
        'Translations',
        'Screenings' => ['Viewers'],
        'Media'
      ],
      'conditions' => ['Films.id' => $id]
    ]);
    $film = $film->first();
    
    $this->set(compact('film'));
    $this->_form($film);
  }
  
  private function _form($film) {
    
    if ($this->request->is(['post', 'put'])) {
      
      $screeningIds = [];
      
      foreach ($this->request->data['screenings'] as $screeningKey => $screening) {
        
        // TODO validate unique location
        if (! empty($screening['new_location'])) {
          $this->request->data['screenings'][$screeningKey]['location_id'] =
            $this->Films->Screenings->Locations->addOnTheFly(trim($screening['new_location']));
        }
        
        // TODO validate unique viewers
        if (! empty($screening['new_viewers'])) {
          $newViewers = explode(",", $screening['new_viewers']);
          foreach ($newViewers as $viewerName) {
            $this->request->data['screenings'][$screeningKey]['viewers']['_ids'][] =
              $this->Films->Screenings->Viewers->addOnTheFly(trim($viewerName));
          }
        }
        
        $screeningIds[] = $screening['id'];
      }
      
      // Delete unwanted screenings
      foreach ($film->screenings as $savedScreeningKey => $savedScreening) {
        if (! in_array($savedScreening->id, $screeningIds)) {
          $screening = $this->Films->Screenings->get($savedScreening->id);
          $this->Films->Screenings->delete($screening);
        }
      }
      
      // Delete erased translation
      if (empty($this->request->data['translation']['title'])) {
        if (!empty($film->translation)) {
          $translation = $this->Films->Translations->get($film->translation->id);
          $this->Films->Translations->delete($translation);
        }
        unset($this->request->data['translation']);
      }
      
      $this->Films->patchEntity($film, $this->request->data, ['associated' => [
        'Translations',
        'Media',
        'Screenings.Viewers'
      ]]);
      
      if ($this->Films->save($film)) {
        $this->Flash->success('Film saved.');
        return $this->redirect(['action' => 'index']);
      }
      else
        $this->Flash->error('Film was not saved.');
    }
    
    $years = $this->Films->years();
    $media = $this->Films->Media->find('list')->toArray();
    $locations = $this->Films->Screenings->Locations->find('list')->toArray();
      $locations['new_location'] = '-- New Venue --';
    $viewers = $this->Films->Screenings->Viewers->find('list')->toArray();
      $viewers['new_viewers'] = 'New Friends';
    
    $this->set(compact('years', 'media', 'locations', 'viewers'));
    $this->render('_form');
  }
  
  public function delete($id) {
    $film = $this->Films->get($id);
    if($this->Films->delete($film))
      return $this->redirect(['action' => 'index']);
  }
  
}
