<?php 

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function simple_service_support_dashboard_widget_function() {


    global $wpdb;
    $table_name = $wpdb->prefix . 'cte'; // tables prefix
/*
    $total_items_wydany         = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE status_zlecenia LIKE 'Wydany'");
    $total_items_przyjety       = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE status_zlecenia LIKE 'Przyjęty do serwisu'");
    $total_items_oczekiwanie    = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE status_zlecenia LIKE 'Oczekiwanie na części'");
    $total_items_naprawiany     = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE status_zlecenia LIKE 'W trakcie naprawy'");

    echo "<ul> 
          <li><span style='float:left; margin-right: 8px;'>". $total_items_wydany    ."</span> Wydanych urządzeń</li>
          <li><span style='float:left; margin-right: 8px;'>". $total_items_przyjety  ."</span>Przyjętych do serwisu </li>
          <li><span style='float:left; margin-right: 8px;'>". $total_items_oczekiwanie."</span> Czeka na części </li>
          <li><span style='float:left; margin-right: 8px;'>". $total_items_naprawiany."</span> W trakcie naprawy </li>
          </ul>
          ";*/


          $sql = $wpdb->get_col("SELECT COUNT(status_zlecenia) FROM $table_name GROUP BY status_zlecenia"); //pokazuje liczbę wystąpień
          $sql2 = $wpdb->get_col("SELECT status_zlecenia FROM $table_name GROUP BY status_zlecenia"); //pokazuje unikalne wystąpienie statusu
          $sql3 = $wpdb->get_var("SELECT COUNT(id) FROM $table_name "); //pokazuje sumę zleceń

          ?>

          <div style="display:inline-flex;">


          <div> 
          <?php
              foreach ($sql as $option_przedmiot_zlecenia): 
                echo $option_przedmiot_zlecenia . "  <br>"; 
              endforeach; 
              echo "--- <br><strong><a href='/wp-admin/admin.php?page=zlecenia'>". $sql3."</a></strong>" ;
          ?>
            
          </div>
          <div>
          <?php
              foreach ($sql2 as $option_przedmiot_zlecenia): 
                echo " - " . $option_przedmiot_zlecenia . "<br>"; 
              endforeach;  ?>
              <br><a href='/wp-admin/admin.php?page=zlecenia'><strong> - Suma</a></strong>
         

          </div>
          </div>
          <?php 
              
} 
