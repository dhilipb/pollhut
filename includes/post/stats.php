
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js"></script>
<script type="text/javascript">
  function initialize() {
    <?php
    $coordinates = array();
    $lattotal = 0;
    $lontotal = 0;
    
    foreach($post->options as $opt) {
		$locquery = select("latitude, longitude", "tbl_votes", "WHERE option_id = '".$opt->id."'");
		while ($loc = assoc($locquery)) {

			$latlon = $loc["latitude"] . ", " . $loc["longitude"];
			if (array_key_exists($latlon, $coordinates)) {
				$coordinates[$latlon]++;
			} else {
				$lattotal += floatval($loc["latitude"]);
				$lontotal += floatval($loc["longitude"]);
				
				$coordinates[$latlon] = 1;
			}
		}
	}
	
	$totcount = count($coordinates);
	?>
	var map = new google.maps.Map(document.getElementById('map_canvas'), {
          zoom: 5,
          center: new google.maps.LatLng(<?=$lattotal/$totcount?>,<?=$lontotal/$totcount?>),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });
	
    var markers = [];
	
	<?php
	foreach($coordinates as $latlng => $count) {
	     for ($i = 0; $i < $count; $i++) {
	?>
	
	var markerImage = new google.maps.MarkerImage('http://chart.apis.google.com/chart?cht=mm&chs=24x32&' +
          'chco=FFFFFF,008CFF,000000&ext=.png', new google.maps.Size(24, 32));
	markers.push(new google.maps.Marker({
					position: new google.maps.LatLng(<?=$latlng?>), 
					icon: markerImage
				}));
   	<?php
	     }
	}
    ?>
    
    var markerClusterer = new MarkerClusterer(map, markers);
  }

  google.maps.event.addDomListener(window, 'load', initialize);
</script>

<table width=100% cellspacing=0 cellpadding=5 id="post_stats">
	<tr>
		<th>Map</th>
		<th>Gender</th>
	</tr>
	<tr>
		<td width='70%' rowspan=3>
			<div id="map_canvas"></div>			
		</td>
		<td>
			<div>
				<div class="chart novote">
				<?php
					require_once "includes/chart/get.php";
					$genderArray = array();
					$genderQry = select("*", "view_stats_post_gender", "WHERE post_id='".$post->id."'");
					while($genderRow = assoc($genderQry)) {
						$gender = empty($genderRow["gender"]) ? "Unknown" : ucwords($genderRow["gender"]);
						$genderArray[$gender] = $genderRow["count"]; 
					}
					if (!array_key_exists("Male", $genderArray)) $genderArray["Male"] = 0;
					if (!array_key_exists("Female", $genderArray)) $genderArray["Female"] = 0;
					// if (!array_key_exists("Unknown", $genderArray)) $genderArray["Unknown"] = 0;
					
					drawArrayChart($genderArray);
				?>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<th>Age</th>
	</tr>
	<tr>
		<td>
			<div>
				<div class="chart novote">
				<?php
					$ageArray = array();
					$ageQry = select("*", "view_stats_post_age", "WHERE post_id='".$post->id."'");
					
					while($ageRow = assoc($ageQry)) {
						$age = empty($ageRow["age"]) ? "Unknown" : $AGES[$ageRow["age"]];
						$ageArray[$age] = $ageRow["count"]; 
					}
					foreach($AGES as $k => $v) {
						if (!array_key_exists($v, $ageArray)) 
							$ageArray[$v] = 0;	
					}

					drawArrayChart($ageArray, false);
				?>
				</div>
			</div>
		</td>
	</tr>
</table>

