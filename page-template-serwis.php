<?php
/*
 * Template Name: Serwis
 */
 
/*  CONFIGURATION START  */

try {
  $config = array(
    'host' => '172.16.0.3',  // propably localhost
    'user' => 'tadeksasnal_firm', // db user
    'pass' => '5AKht90fl2kg', // db pass
    'db'   => 'tadeksasnal_firm' // db name
    );
$pdo = new PDO("mysql:host={$config['host']}; dbname={$config['db']};", $config['user'], $config['pass'] );
$pdo -> query ('SET NAMES utf8');
$pdo -> query ('SET CHARACTER_SET utf8_general_ci');

}
catch (PDOException $e){
  die('Wystąpił błąd z PDO: '.$e->getMessage());
}

/*  CONFIGURATION END  */


  get_header();

      global $wpdb;
      $table_name = $wpdb->prefix . 'cte'; // tables prefix

  add_action( 'wp_enqueue_scripts', 'add_thickbox' );

?>

    <?php /* jeżeli nie wypełniono formularza - to znaczy nie istnieje zmienna numer_seryjny, i telefon to wyświetl formularz logowania */ 
        if (!isset($_POST[ 'numer_seryjny']) ) { ?>
        <div class="wyszukiwanie">
            <form id="klient_form" name="klient_form" action="/serwis/status/" method="post">
              <p>Numer seryjny: <br>
                  <input type="text" name="numer_seryjny" id="numer_seryjny" size="20" class="input" required>
              </p>
              <p class="submit">
                  <input type="submit" name="klient_form" value="Szukaj" class="button" title="Szukaj w bazie danych"/>
                  <input type="reset" value="Reset" class="button" title="Wyczyść formularz"/>
              </p>
            </form>

            <p>Naklejka z numerem seryjnym znajduje się najczęsciej na spodzie urządzenia</p>
            <p>Numer seryjny znajduje się obok “Serial No :” lub "SN:"</p>
            <img src="https://www.asus.com/support/images/products/model-notebook.jpg">
        </div>

    <div class="client_stats">
      <h2>Najczęściej serwisowana marka laptopa</h2>
      <div class="warning-message message"> UWAGA - marka najczęsciej serwisowana nie oznacza że jest najgorsza. Może to zonaczać, że jest jej najwięcej na rynku.  </div>
      <?php

          require_once(ABSPATH. 'wp-content/plugins/oko/stats_client.php'); 
          simple_service_support_zlecenia_stats_client ();  // Pokazywanie strony ze statystykami -> w osobnym pliku stats.php
    ?> 
  </div> 
<?php
    } 

    elseif (isset($_POST['numer_seryjny'])) {
      
        // jeżeli pole z numer_seryjnyem i hasłem nie jest puste      
    if (!empty($_POST['numer_seryjny']) ) {

  $numer_seryjny = $_POST['numer_seryjny'];

  $sql = "SELECT * FROM $table_name WHERE numer_seryjny = ?";
        $result = $pdo->prepare($sql); 
        $result->bindValue(1, $numer_seryjny, PDO::PARAM_STR);
        $result->execute();

  $num = (int) $result->rowCount();           // liczy znalezione wiersze
    
      // jeżeli powyższe zapytanie zwraca 1, to znaczy, że dane zostały wpisane poprawnie 
    if ($num == 1) {
?>
    <table class="shop_table">
      
        <?php
        foreach ($result as $key ) {
    
        echo '<thead><tr><th style="width:50%;"> Szczegóły zlecenia nr:  </th><th style="width:50%;">'. $key['id']        .'</th></tr> </thead>'; 
        echo '<tbody>      <tr><td> Klient:         </td><td>'. $key['name']      .'</td></tr>';
        echo '         <tr><td> Przedmiot Zlecenia: </td><td>'. $key['przedmiot_zlecenia'].'</td></tr>';
        echo '         <tr><td> Marka:        </td><td>'. $key['brand']       .'</td></tr>';
        echo '         <tr><td> Model:        </td><td>'. $key['model']       .'</td></tr>';
        echo '         <tr><td> Numer Seryjny:    </td><td>'. $key['numer_seryjny'] .'</td></tr>';
        echo '         <tr><td> Wyposażenie:        </td><td>'. $key['wyposazenie'] .'</td></tr>';
        //echo '         <tr><td> Uwagi do urządzenia:</td><td>'. $key['uwagi']     .'</td></tr>';
        echo '         <tr><td> Opis usterki:     </td><td>'. $key['opis_usterki']  .'</td></tr>';
        echo '         <tr><td> Informacje dla klienta:</td><td>'. $key['info_dla_klienta'].'</td></tr>';
        //echo '         <tr><td> Kwota do zapłaty:   </td><td>'. $key['cena']      .'</td></tr>';  
        echo '         <tr><td> Status Zlecenia:    </td><td>'. $key['status_zlecenia'].'</td></tr>';
        echo '         <tr><td> Data Przyjęcia:   </td><td>'. $key['delivery_date'].'</td></tr>';
        echo '         <tr><td> Data Wydania:     </td><td>'. $key['data_wydania']  .'</td></tr>';

          $image_1 = $key['image_1'];
          /*$wynik_1 = str_replace("-300x200.", ".", $image_1);*/

          $image_2 = $key['image_2'];
          $wynik_2 = str_replace("-300x200.", ".", $image_2);

          $image_3 = $key['image_3'];
          $wynik_3 = str_replace("-300x200.", ".", $image_3);

          $image_4 = $key['image_4'];
          $wynik_4 = str_replace("-300x200.", ".", $image_4);


          echo '        <tr><td> <a href="'.$image_1.'"class="thickbox"> <img class="img-responsive" src="'. $image_1 .'"></a> </td>  ';                           
          echo '            <td> <a href="'.$wynik_2.'"class="thickbox"> <img class="img-responsive" src="'. $image_2 .'"></a> </td></tr>  ';
          echo '        <tr><td> <a href="'.$wynik_3.'"class="thickbox"> <img class="img-responsive" src="'. $image_3 .'"></a> </td>  ';         
          echo '            <td> <a href="'.$wynik_4.'"class="thickbox"> <img class="img-responsive" src="'. $image_4 .'"></a> </td></tr>  
        </tbody> ';
      }
    ?>
      
    </table>
<?php }
            
     // jeżeli zapytanie nie zwróci 1, to wyświetlam komunikat o błędzie podczas wyszukiwania
      else {
        echo '<p class="komunikat">Nic nie znaleziono!<br /> Sprawdź wprowadzone dane i spróbuj ponownie.<br />';
        echo '<a href="">Wróć do formularza</a></p>';
      } 
    }
        
        // jeżeli pole numer_seryjny nie zostało uzupełnione wyświetlam błąd
    else {
      echo '<p class="komunikat">Błąd wyszukiwania!<br> Proszę wypełnić wymagane pola.<br />';
      echo '<a href="">Wróć do formularza</a></p>'; 
    }
  }
?>

<?php get_footer(); ?>
