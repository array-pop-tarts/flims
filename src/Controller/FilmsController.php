<?php

namespace App\Controller;

class FilmsController extends AppController
{
  public function index() {
    
    $films = $this->Films->find('all')
      ->contain([
        'Translations',
        'Media',
        'Screenings' => [
          'Locations',
          'Viewers'
        ]
      ]);
      
    $films = $this->paginate($films);
    $this->set(compact('films'));
  }
  
  public function add() {
    
    $film = $this->Films->newEntity();
      $film['translation'] = $this->Films->Translations->newEntity();
      $film['media'] = [0 => $this->Films->Media->newEntity()];
    
      $screenings = $this->Films->Screenings->newEntity();
        $screenings['location'] = $this->Films->Screenings->Locations->newEntity();
        $screenings['viewers'] = $this->Films->Screenings->Viewers->newEntity();
      $film['screenings'] = [0 => $screenings];
    
    $this->set(compact('film'));
    $this->_form($film);
  }
  
  public function edit($id) {
    $film = $this->Films->find('all', [
      'contain' => [
        'Translations',
        'Media',
        'Screenings' => [
          'Viewers',
          'Locations'
        ]
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
        // Add new location
        if (! empty($screening['new_location'])) {
          $this->request->data['screenings'][$screeningKey]['location_id'] =
            $this->Films->Screenings->Locations->addOnTheFly(trim($screening['new_location']));
        }
        // Add new viewers
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
