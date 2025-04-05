<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Location Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
    integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
    crossorigin=""/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../home.css">
    <style>
        :root {
            --primary-color: #06C167;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --shadow: 0 10px 20px rgba(0,0,0,0.2);
            --deep-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
        }
        
        header {
            background: white;
            box-shadow: var(--shadow);
            transform: translateZ(10px);
            transition: all 0.3s ease;
        }
        
        .logo {
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .nav-bar ul li a {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-bar ul li a:hover {
            color: var(--primary-color);
            transform: translateY(-3px);
        }
        
        .nav-bar ul li a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            background: var(--primary-color);
            bottom: -5px;
            left: 0;
            transition: width 0.3s;
        }
        
        .nav-bar ul li a.active::after,
        .nav-bar ul li a:hover::after {
            width: 100%;
        }
        
        #contain {
            background: white;
            border-radius: 20px;
            box-shadow: var(--deep-shadow);
            padding: 20px;
            margin: 20px auto;
            max-width: 900px;
            transform-style: preserve-3d;
            transform: perspective(1000px);
            transition: all 0.5s ease;
        }
        
        #contain:hover {
            transform: perspective(1000px) translateY(-10px);
        }
        
        h3 {
            color: var(--secondary-color);
            font-size: 1.8rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            position: relative;
        }
        
        h3::after {
            content: '';
            position: absolute;
            width: 50px;
            height: 4px;
            background: var(--primary-color);
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }
        
        #map-container {
            width: 100%;
            height: 400px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transform: translateZ(0);
            transition: all 0.3s ease;
            margin: 20px 0;
        }
        
        #map-container:hover {
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            transform: translateZ(10px);
        }
        
        .leaflet-popup-content {
            font-family: 'Poppins', sans-serif;
        }
        
        .leaflet-popup-content b {
            color: var(--primary-color);
        }
        
        #city-name, #address {
            background: var(--light-color);
            padding: 12px 20px;
            border-radius: 50px;
            margin: 10px 0;
            display: inline-block;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateZ(0);
            transition: all 0.3s ease;
        }
        
        #city-name:hover, #address:hover {
            transform: translateY(-3px) translateZ(5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        
        /* 3D floating animation */
        @keyframes float {
            0% { transform: translateY(0px) rotateY(0deg); }
            50% { transform: translateY(-10px) rotateY(5deg); }
            100% { transform: translateY(0px) rotateY(0deg); }
        }
        
        @media screen and (max-width: 600px) {
            #map-container {
                height: 250px;
            }
            
            #contain {
                padding: 15px;
                margin: 10px;
            }
            
            h3 {
                font-size: 1.4rem;
            }
        }
        
        /* Custom marker icon */
        .custom-marker-icon {
            background: var(--primary-color);
            border: 3px solid white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            transform: translateZ(0);
            transition: all 0.3s ease;
        }
        
        .custom-marker-icon:hover {
            transform: scale(1.2) translateZ(10px);
        }
    </style>
</head>
<body>
<header>
    <div class="logo">Food <b style="color: var(--primary-color);">Donate</b></div>
    <div class="hamburger">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <nav class="nav-bar">
        <ul>
            <li><a href="delivery.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="#" class="active"><i class="fas fa-map-marked-alt"></i> Map</a></li>
            <li><a href="deliverymyord.php"><i class="fas fa-clipboard-list"></i> My Orders</a></li>
            <li><a href="logout.php"><i class="fas fa-clipboard-list-arrow-left"></i>LOGOUT</a></li>
        </ul>
    </nav>
</header>

<script>
    hamburger = document.querySelector(".hamburger");
    hamburger.onclick = function(){
        navBar = document.querySelector(".nav-bar");
        navBar.classList.toggle("active");
    }
</script>

<div id="contain">
    <h3><i class="fas fa-location-dot"></i> Current Location</h3>
    <div id="map-container"></div>
    <div id="city-name"></div>
    <div id="address"></div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.min.js"></script>
<script>
    // Initialize the map and user's location marker
    function initMap() {
        var mapContainer = document.getElementById("map-container");
        
        navigator.geolocation.getCurrentPosition(function(position) {
            var userLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            
            var map = L.map(mapContainer).setView(userLocation, 15);
            
            // Add the OpenStreetMap tile layer with 3D effect
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
                maxZoom: 18,
                tileSize: 512,
                zoomOffset: -1
            }).addTo(map);
            
            // Create a custom marker icon
            var customIcon = L.divIcon({
                className: 'custom-marker-icon',
                html: '<i class="fas fa-location-dot"></i>',
                iconSize: [30, 30]
            });
            
            // Add a marker at the user's location
            var marker = L.marker(userLocation, {icon: customIcon}).addTo(map);
            marker.bindPopup("<b><i class='fas fa-map-pin'></i> You are here!</b>").openPopup();
            
            // Add a circle to indicate the accuracy of the user's location
            var accuracyCircle = L.circle(userLocation, {
                radius: position.coords.accuracy,
                fillColor: "#06C167",
                fillOpacity: 0.2,
                color: "#06C167",
                weight: 1
            }).addTo(map);
            
            // Retrieve location details using OpenStreetMap API
            var url = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=" +
                     userLocation.lat + "&lon=" + userLocation.lng;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    var cityName = data.address.city || data.address.town || data.address.village;
                    document.getElementById("city-name").innerHTML = 
                        `<i class="fas fa-city"></i> You are in ${cityName}, ${data.address.country}`;
                    
                    document.getElementById("address").innerHTML = 
                        `<i class="fas fa-map-location-dot"></i> ${data.display_name}`;
                })
                .catch(error => {
                    console.error("Error fetching location data:", error);
                });
            
            // Add zoom controls with better styling
            L.control.zoom({
                position: 'topright',
                zoomInText: '<i class="fas fa-plus"></i>',
                zoomOutText: '<i class="fas fa-minus"></i>'
            }).addTo(map);
            
        }, function(error) {
            alert("Error: Could not retrieve your location. " + error.message);
        });
    }
    
    // Initialize the map when the page loads
    document.addEventListener('DOMContentLoaded', initMap);
</script>
</body>
</html>