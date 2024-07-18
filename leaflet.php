<?php
session_start ();
if (isset ( $_SESSION ['investigation'] ) === false) {
	echo "<h3>Veuillez selectionner une investigation et un pathogène</h3>";
	exit ();
}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<link rel="icon" type="image/gif" href="images/Institut_Pasteur.gif" />


<link rel="stylesheet" href="leaflet/leaflet.css" />
<link rel="stylesheet" type="text/css" media="all" href="../css/css.css" />
<link rel="stylesheet" href="../css/iThing-min.css">

<script src="leaflet/leaflet.js"></script>
<script src="../js/jquery.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<script src="../js/jQDateRangeSlider-min.js"></script>

<style>
#slider {
	max-width: 900px;
	margin: 50px auto;
}

#map {
	height: 450px;
}
</style>

<title>Institut Pasteur</title>
</head>
<body>
	<center>
		<div id="conteneur">
			<div id="div_course">
				<div style="text-align:left;">
					<table>
						<tr>
							<td>
								<label style="font-size:11px; font-style:italic;">Rechercher un Individu: </label>
								<br/>
								<input type="text" id="patient_search" class="numbersOnly" />
								<button id="btn_search" style="border-radius:10px;">Go</button>
								<a href="javascript:void(0);" 
				                   class="class_button_clear" 
				                   onclick="$('#patient_search').val('');">
				                    <img src="../images/clear.png" class="class_clear_img" />
				                </a>
							</td>
							<td style="width: 50px;"></td>
							<td  >
								<label style="font-size:11px; font-style:italic;">Rayon: </label>
								<br/>
								<select type="text" id="rayon" style="width: 50px;" >
								<?php for($i=100; $i<=1000; $i=$i+100){ ?>
								<option value="<?php echo($i); ?>"><?php echo($i); ?></option>
								<?php } ?>
				                </select>&nbsp;<label style="font-weight:normal;">m&egrave;tres</label>
							</td>
						</tr>
					</table>
					
					<div id="sampleSlider" style="width:75%" ></div>
				</div>

				<div id="head">
					<br />
					<div id="map"></div>
					<script type="text/javascript">
					
	                var iconNeg = L.icon({
	      				iconUrl: 'leaflet/images/negatif.png',
	      				iconSize: [14, 14], // size of the icon
	      			    iconAnchor: [7, 14], // point of the icon which will correspond to marker's location
	      			    popupAnchor: [0, -14] // point from which the popup should open relative to the iconAnchor    
			        });
			        
					var iconPos = L.icon({
			            iconUrl: 'leaflet/images/positif.png',
			            iconSize: [14, 14], // size of the icon
	      			    iconAnchor: [7, 14], // point of the icon which will correspond to marker's location
	      			    popupAnchor: [0, -14] // point from which the popup should open relative to the iconAnchor 
			        });

					var iconElse = L.icon({
			            iconUrl: 'leaflet/images/autres.png',
			            iconSize: [14, 14], // size of the icon
	      			    iconAnchor: [7, 14], // point of the icon which will correspond to marker's location
	      			    popupAnchor: [0, -14] // point from which the popup should open relative to the iconAnchor 
			        });
			        
					var cities = L.layerGroup();
	
					var mbAttr = 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
							'<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
							'Imagery © <a href="http://mapbox.com">Mapbox</a>',
						mbUrl = 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';
	
					var grayscale   = L.tileLayer(mbUrl, {id: 'mapbox.light', attribution: mbAttr}),
						streets  = L.tileLayer(mbUrl, {id: 'mapbox.streets',   attribution: mbAttr});
	
					var map = L.map('map', {
						center: [15.621377, -16.225606],
						zoom: 13,
						layers: [cities, streets]
					});
	
					var baseLayers = {
						"Streets": streets,
						"Grayscale": grayscale
					};
	
					var overlays = {
						"Cities": cities
					};

					function removeCircle(e) {
						var obj = this;
						alertify.confirm("Voulez-vous effacer le cercle?", function (e) {
							if (e) {
								map.removeLayer(obj);    
					        } else {
					            return true;
					        }
					    });
	                }
	                
	                var rayon = 200;

	                function markerOnClick(lat, lng) {
	                    L.circle([lat,lng],
	                    		rayon,
	    	                {
	    	                	color:'blue',
   	                     		opacity:0,
   	                     		fillColor: 'blue',
   	                     		fillOpacity:.4
   	                     	}).addTo(map).on('click', removeCircle);
	                }

	                
	                /* Numeric only */
	                jQuery('.numbersOnly').keyup(function () { 
	                    this.value = this.value.replace(/[^0-9\.]/g,'');
	                });
	                
	                function getMinBound(arr, val){
						var ind = arr.indexOf(val);
						if(ind != -1){
							return ind;
						}
						for(var i=0; i<arr.length; i++){
							if(String(val) < String(arr[i])){
								return i;
							}
						}
						return 0;
	                }
	               
	                function getMaxBound(arr, val){
						var ind = arr.indexOf(val);
						if(ind != -1){
							return ind;
						}
						for(var i=(arr.length-1); i>=0; i--){
							if(String(val) > String(arr[i])){
								return i;
							}
						}
						return (arr.length);
	                }
	                
	                
                $(document).ready(function() {

                	$("#rayon").val(200); 
                	
                    var markers_array = [];
                    var individuals = [];
					var date_prelevement = [];
					
                     
                    $("#btn_search").click(function(){
						var id = $.trim($("#patient_search").val());
						var ind = individuals.indexOf(id);

						if(ind != -1){
							markers_array[ind].openPopup();
						} else {
							alertify.error("Cet individu n'est pas positionné!!!");
						}
                    });

                    $("#rayon").change(function(){
						rayon = parseInt($("#rayon").val());
					});


                    
				 	$.post('post_map.php', function(data) {
					 	var date_prelevement2 = [];
                    	$.each($.parseJSON(data), function(k, v) {
                    		var lat = parseFloat(v['latitude']);
                    		var lon = parseFloat(v['longitude']);
                    		
                    		if(!isNaN(lat) && !isNaN(lon)){
                        		var sexe = v['sexe'] == 'f' ? "<image src='../images/female.png' />" : v['sexe'] == 'm'? "<image src='../images/male.png' />" : "NR";
                    			var lab = 	 '<b>ID:<b/> ' + v['no_ipd'] +
											 '<br/>' + '<b>Sexe:<b/> ' + sexe + 
											 '<br/>' + '<b>Age:<b/> ' + v['age_annees'] +
											 '<br/>' + '<b>Date prelevement:<b/> ' + v['date_prelevement'] +
											 '<br/><a href="javasscript:void(0)" onclick="markerOnClick('+lat+','+lon+')">Encercler<a/>';
											  
								individuals.push(v['no_ipd']);
								
								date_prelevement.push(v['date_prelevement']);
								date_prelevement2.push(v['date_prelevement']);
								
							    if(v['diagnostic'] == 1){
							    	markers_array.push(L.marker([lat, lon],{icon: iconPos}).addTo(map).bindPopup(lab).openPopup());
								}		           

								else if(v['diagnostic'] == 2){
									markers_array.push(L.marker([lat, lon],{icon: iconNeg}).addTo(map).bindPopup(lab).openPopup());
								}

								else{
									markers_array.push(L.marker([lat, lon],{icon: iconElse}).addTo(map).bindPopup(lab).openPopup());
								}
							}
		            	});
                    	
                    	$("#sampleSlider").dateRangeSlider({
    						bounds:{
    						    min: new Date(date_prelevement2[0]),
    						    max: new Date(date_prelevement2[date_prelevement2.length-1])
    						}
    					});

                    	$("#sampleSlider").bind("valuesChanged", function(e, data){
                    		//console.log("Something moved. min: " + data.values.min + " max: " + data.values.max);
                    		var dateMin    = data.values.min,
                    		    yr      = dateMin.getFullYear(),
                    		    month   = (dateMin.getMonth()+1) < 10 ? '0' + (dateMin.getMonth()+1) : (dateMin.getMonth()+1),
                    		    day     = dateMin.getDate()  < 10 ? '0' + dateMin.getDate()  : dateMin.getDate(),
                    		    newDateMin = yr + '-' + month + '-' + day;
                    		    
                    		var dateMax    = data.values.max,
                    		    yr      = dateMax.getFullYear(),
                    		    month   = (dateMax.getMonth()+1) < 10 ? '0' + (dateMax.getMonth()+1) : (dateMax.getMonth()+1),
                    		    day     = dateMax.getDate()  < 10 ? '0' + dateMax.getDate()  : dateMax.getDate(),
                    		    newDateMax = yr + '-' + month + '-' + day;

                    		// clear all markers
                      		$.each(markers_array, function(i,v){
                    			map.removeLayer(v); 
							});

							var showmarkers = markers_array.slice(
									getMinBound(date_prelevement2, newDateMin),
									getMaxBound(date_prelevement2, newDateMax)
								);

							$.each(showmarkers, function(i,v){
                    			map.addLayer(v); 
							});
							//console.log(newDateMax);
							/*
                      		console.log(
                      				date_prelevement2[getMinBound(date_prelevement2, newDateMin)] + '  '+
                      				date_prelevement2[getMaxBound(date_prelevement2, newDateMax)]
                              		);
                      		*/
                      		
						});
                    });
				
					L.control.layers(baseLayers, overlays).addTo(map);
                });
                    
                </script>

				</div>

				<div id="base"></div>
			</div>
	
	</center>
	<!-- Nice alert -->
	<script src="../alertify/lib/alertify.min.js"></script>
	<link rel="stylesheet" href="../alertify/themes/alertify.core.css" />
	<link rel="stylesheet" href="../alertify/themes/alertify.bootstrap.css" />
</body>
</html>
