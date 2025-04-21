<?php

namespace Figurine\Model;

class Collections {
  private $apiKey = 'fa3ff7c8-d2ab-4e5e-9b53-6886290da4ef';

  /**
   * Récupère les collections d'un créateur donné
   */
  public function getCollectionsByCreator($creator) {
    // On utilise $creator comme variable de recherche
    $query = $creator;
    // Construction de l'URL
    $url = 'https://www.myminifactory.com/api/v2/users/belksasar3dprint/collections?per_page=20' . urlencode($query) . '&key=' . $this->apiKey;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
    $result = curl_exec($ch);
  
    if (curl_errno($ch)) {
      curl_close($ch);
      return null;
    }
    curl_close($ch);
    
    $data = json_decode($result, true);
    // Pour déboguer :
    // echo '<pre>'; print_r($data); echo '</pre>';
    return $data;
  }
}
?>