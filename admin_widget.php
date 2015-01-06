<?php 

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function simple_service_support_dashboard_widget_function() {

    // Display whatever it is you want to show.

    global $wpdb;
    $table_name = $wpdb->prefix . 'cte'; // tables prefix

    $total_items_wydany         = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE status_zlecenia LIKE 'Wydany'");
    $total_items_przyjety       = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE status_zlecenia LIKE 'Przyjety do serwisu'");
    $total_items_oczekiwanie    = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE status_zlecenia LIKE 'Oczekiwanie na czesci'");
    $total_items_naprawiany     = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE status_zlecenia LIKE 'W trakcie naprawy'");

    echo "<ul> 
          <li><span style='float:left; margin-right: 8px;'>". $total_items_wydany    ."</span> Wydanych urządzen</li>
          <li><span style='float:left; margin-right: 8px;'>". $total_items_przyjety  ."</span>Przyjetych do serwisu </li>
          <li><span style='float:left; margin-right: 8px;'>". $total_items_oczekiwanie."</span> Czeka na czesci </li>
          <li><span style='float:left; margin-right: 8px;'>". $total_items_naprawiany."</span> W trakcie naprawy </li>
          </ul>
          ";
    
} 
