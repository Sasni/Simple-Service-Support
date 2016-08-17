<?php

function simple_service_support_zlecenia_slowniki(){

	global $wpdb;
    $table_name = $wpdb->prefix . 'zlecenia_status'; // tables prefix 

    $sql = $wpdb->get_col("SELECT status_zlecenia FROM $table_name GROUP BY status_zlecenia"); //pokazuje zgrupowane unikalne Statusy

    ?>

    <div>
    <span style="font-weight:bold;"> Statusy zleceń </span><br>  
    	<?php     													//u góry jest tytuł tabelki
              foreach ($sql as $option_przedmiot_zlecenia): 		//wypisuje pojedynczo każdą unikalną wartość w inpucie (do edycji)
                echo "<input id='status_zlecenia' name='status_zlecenia' type='text' value='" . $option_przedmiot_zlecenia . "'> <br>"; 
              endforeach; 
        ?>
    </div>

<?php


}
