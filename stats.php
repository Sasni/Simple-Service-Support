<?php

function simple_service_support_zlecenia_stats ()  // Pokazywanie strony ze statystykami
{

    global $wpdb;
    $table_name = $wpdb->prefix . 'cte'; // tables prefix

    $all_brands = $wpdb->get_results("SELECT brand, COUNT(brand) AS liczba FROM $table_name GROUP BY brand"); // Pytanie wypisujące markę oraz zaiczające je.

	echo '<div class="wrap">';?>
	<div style="float:left">
		<!-- <table class="zlecenia striped widefat">
			<thead>
				<tr>
					<th>Marka</th>	<th>Ilość</th>
				</tr>
			</thead>
			<tbody> -->
				<?php 
				foreach ($all_brands as $value): 
				//echo '<tr><td>'.$value->brand.'</td><td>'.$value->liczba.'</td></tr>';
				$do_wykresu[] = "['".$value->brand."', ".$value->liczba."]";
				endforeach;  ?>
			<!-- </tbody>
		</table> -->
	</div>
	<div style="float:left;">
		<?php $data_for_chart = implode(",", $do_wykresu); ?>  <!-- oddziela wartości tablicy przecinkami - dane z pętli foreach -->

		<!-- WYKRES GOOGLE -->
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	    <script type="text/javascript">

	      // Load the Visualization API and the piechart package.
	      google.load('visualization', '1.1', {'packages':['table']});

	      // Set a callback to run when the Google Visualization API is loaded.
	      google.setOnLoadCallback(drawTable);

	      // Callback that creates and populates a data table,
	      // instantiates the pie chart, passes in the data and
	      // draws it.
	      function drawTable() {

	        // Create the data table.
	        var data = new google.visualization.DataTable();
	        data.addColumn('string', 'Marka');
	        data.addColumn('number', 'Ilość');
	        data.addRows([
	          <?php echo $data_for_chart; ?>
	        ]);

	        // Set chart options
	        var options = {'title':'Laptopy',
	                       'width':400
	                       };

	        // Instantiate and draw our chart, passing in some options.
	        var table = new google.visualization.Table(document.getElementById('table_div'));
	        table.draw(data, options);
	      }
	    </script>
		<div id="table_div"></div>
		<!-- KONIEC WYKRESU GOOGLE -->

	</div>
	<div style="clear:both;"></div>

<?php

	echo '<p>Here is where the form would go if I actually had options.</p>';
	echo '</div>';

}
?>