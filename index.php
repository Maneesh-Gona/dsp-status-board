<!--
		DELTA SIG STATUS BOARD
		Webmasters:
			-Jake Beinart - Fall 2016
			-
-->
<html>
	<head>
		<title>DSP Status Board</title>
		<meta content="text/html; charset=UTF-8" http-equiv="content-type">
		<!-- jQuery CDN -->
		<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
		<!-- Bootstrap js CDN -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<!-- Bootstrap css CDN -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
	</head>
	
	<script>
	var obj;
	var photos_index=0;
		

	function refresh(){
		$.get("dataserve.php", 
			function(data_json){
				
				obj = JSON.parse(data_json);
				console.log(obj);
				//Date and Time
				document.getElementById('time').innerHTML = '<h1>' + obj.time + '</h1>';
				document.getElementById('date').innerHTML = '<h3>' + obj.date + '</h3>';
				
				//Current Weather
				document.getElementById('current_words').innerHTML = '<h3>' + obj.current_weather.weather_description + '<h3>';
				document.getElementById('current_temperature').innerHTML = '<h1>' + Math.round(obj.current_weather.current_temp) + '&deg' + '<h1>'; 
				document.getElementById('current_icon').innerHTML = '<img src="http://openweathermap.org/img/w/' + obj.current_weather.weather_icon + '.png"  style="width:80px; height:80px;">';
				
				//Forecast Weather
				var forecast_html = "";
				
				Object.keys(obj['forecast_data']).forEach(function (key) {
					forecast_html += '<div class="col-lg-2"><div class="card"><h1 class="card-header" style="background-color: #eac45d; color: white;">' + obj['forecast_data'][key]['day'] + '</h1><div class="row"><div class="col-lg-4 text-lg-center"><img src="' + obj['forecast_data'][key]['icon_src'];
					forecast_html += '" style="width:80px; height:80px;"></div><div class="col-lg-8" style="height:115px;"><p class="card-text"><div id="high_low"><h2><b>' + obj['forecast_data'][key]['max'] + '</b>|'+obj['forecast_data'][key]['min']+'</h2></div>';
					forecast_html += '</p></div></div></div></div>'
				})
				
				//New announcements
				//$("#announcements").load("announcements.html"); 
				
				//Athletic Events
				if (!("athletics_data" in obj))
				{
					athletics_calendar_html = "<h3 style='margin-left: 25%;margin-top:35%;'>No more calendar events!</h3>";
				}
				else
				{
					var athletics_calendar_html = '<table class="table"><tbody style="font-size:18px;">';
				
					Object.keys(obj['athletics_data']).forEach(function (key) {
						if(obj['athletics_data'][key]['day'] == "Today")
						{
							athletics_calendar_html += '<tr style="background-color:#a0a0a0;border-bottom: 2px solid white;"><td>';
						}
						else
						{
							athletics_calendar_html += '<tr style="background-color:#f0f0f5;border-bottom: 2px solid white;"><td>';
						}
						athletics_calendar_html += obj['athletics_data'][key]['name'] + '</td><td style="width: 110px;">' + obj['athletics_data'][key]['time'] + '</td><td style="width: 130px;">' + obj['athletics_data'][key]['day'] + '</td></tr>';
					})
					athletics_calendar_html += '</tbody></table>';
				}
				
				//Upcoming Events
				if(!("upcoming_data" in obj))
				{
					upcoming_calendar_html = "<h3 style='margin-left: 25%;margin-top:35%;'>No upcoming events!</h3>";
				}
				else
				{
					var upcoming_calendar_html = '<table class="table"><tbody style="font-size:18px;">';
					
					Object.keys(obj['upcoming_data']).forEach(function (key) {
						if(obj['upcoming_data'][key]['day'] == "Today")
						{
							upcoming_calendar_html += '<tr style="background-color:#a0a0a0;border-bottom: 2px solid white;"><td>';
						}
						else
						{
							upcoming_calendar_html += '<tr style="background-color:#f0f0f5;border-bottom: 2px solid white;"><td>';
						}
						
						upcoming_calendar_html += obj['upcoming_data'][key]['name'] + '</td><td style="width: 110px;">' + obj['upcoming_data'][key]['time'] + '</td><td style="width: 130px;">' + obj['upcoming_data'][key]['day'] + '</td></tr>'
					})
					upcoming_calendar_html += '</tbody></table>';
				}
				
				//Today Calendar
				document.getElementById('athletics_calendar').innerHTML = athletics_calendar_html;
				
				//Upcoming Calendar
				document.getElementById('upcoming_calendar').innerHTML = upcoming_calendar_html;
				
				//Forecast
				document.getElementById('forecast').innerHTML = forecast_html;
				
				//Screensaver every 30 minutes to prevent screen burning if necessary
				var d = new Date();
				if (d.getMinutes() % 30 == 0)
				{
					document.getElementById("screensaver").style.opacity = "1";
				}
				else
				{
					document.getElementById("screensaver").style.opacity = "0";
				}
			}
			);
		}
		
		function refresh_photo(){
			//Flickr Photos
			var photos_html = '<div style="background-image:url(' + obj['flickr_data'][photos_index]['url'] + ');max-height:515px; width:100%; height:100%; background-size:contain; background-repeat: no-repeat; background-position: center;">'; //class="img-fluid m-x-auto d-block" 
			photos_index = (1 + photos_index) % 500;
			document.getElementById('photo').innerHTML = photos_html;

		}
		
		refresh();
		
		setInterval(function(){
			refresh();
		}, 30000);
		setInterval(function(){
			refresh_photo();
		}, 5000);
	
	</script>
	
	<body style="background-color:black;">
		
		<div class="container-fluid">
			<div class="row">
			
				<div class="col-lg-3" id="crest">
					<img src="pledge_pin.svg" style="width:130px; height:130px; margin:10px;">
				</div>
				
				<div class="col-lg-6 text-lg-center" id="title" style="margin-top:25px;color:white;">
					<h1 style="font-size: 80px;">ΔΣΦ - ΔΕ</h1>
				</div>
				
				<div class="col-lg-3" id="datetime" style="margin-top:30px;color:white;">
					<div class="row text-lg-center" id="time">
					</div>
					<div class="row text-lg-center" id="date">
					</div>
				</div>
				
			</div>
			<div class="row">
				<div class="col-lg-12 text-lg-center">
					<div class="card card-inverse" style="background-color: #003c2e;">
							<h1 class="card-title" style="margin-top:10px;">
								<b>Announcements:</b> Happy Thanksgiving!
							</h1>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-4">
					<div class="card">
						<div class="card-header" style="background-color: #76128b; color: white; ">
							<h1 class="text-lg-center">Upcoming Events</h1>
						</div>
						<div id="upcoming_calendar" style="height:515px;overflow:hidden;">
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="card">
						<div class="card-header" style="background-color: #76128b; color: white;">
							<h1 class="text-lg-center">Athletic Events</h1>
						</div>
						<div id="athletics_calendar" style="height:515px;overflow:hidden;">
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="card">
						<div class="card-header" style="background-color: #76128b; color: white;">
							<h1 class="text-lg-center" >Photos</h1>
						</div>
						<div id="photo" style="height:515px;">
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-lg-3" id="current_weather">
					<div class="card">
						<h1 class="card-header" style="background-color: #eac45d; color: white;">Current Weather</h1> <!--5bc0de -->
						<div class="row" style="height:115px;margin-right:0px;padding-right:0px;">
							<div class="col-lg-3 text-lg-center">
								<div id="current_icon" alt="Card image"></div>
							</div>
							<div class="col-lg-9 no-gutter">
									<p class="card-text">
										<div id="current_temperature"></div>
										<div id="current_words"></div>
									</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-9" id="forecast" style="margin:0px;padding:0px;">
				</div>
			</div>
		</div>
		<div id="screensaver" style="position:absolute;height:100%;width:100%;background-image:url(flag.jpg);background-color:black;top:0px;background-size:contain; background-repeat: no-repeat; background-position: center;"></div> <!-- In case we need a picture overlay at any time -->
	</body>
	
</html>