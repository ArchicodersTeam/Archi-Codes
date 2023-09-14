<?php

add_shortcode('custom_windy', function () {
    ob_start();
?>
    <div id="custom-weather-wrapper">
        <div class="map-form">
            <div class="input-wrapper">
                <label for="origin">
                    Location:
                </label>
                <input type="text" id="location" list="locations" value="Australia" autocomplete="off">
                <datalist id="locations">
                    <option>Australia</option>
                </datalist>
            </div>
        </div>
        <iframe id="custom-windy" width="650" height="450" src="https://embed.windy.com/embed2.html?width=650&height=450&zoom=4&level=surface&overlay=wind&product=ecmwf&menu=&message=&marker=&calendar=now&pressure=&type=map&location=coordinates&detail=true&metricWind=km%2Fh&metricTemp=%C2%B0C&radarRange=-1&lat=-24.7761086&lon=134.755&detailLat=-24.7761086&detailLon=134.755" frameborder="0"></iframe>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function initiateMAP() {
                    const location = document.getElementById('location')
                    const map = document.getElementById('custom-windy')

                    location.addEventListener('input', async function() {
                        const params = getMapParams(map)
                        const locationObj = await fetchGeolocation(this.value)
                        document.getElementById('locations').innerHTML = locationObj.map(
                            loc => `<option data-lat="${loc.lat}" data-lon="${loc.lon}">${loc.display_name}</option>`
                        ).join('')

                        if (locationObj)
                            map.src = createCustomURL({
                                ...params,
                                location: locationObj[0]
                            })
                    })
                }

                function getMapParams(iframe) {
                    const url = iframe.src

                    // Create a URL object
                    const urlObj = new URL(url)

                    // Get the search parameters
                    const searchParams = urlObj.searchParams

                    // Now you can access individual parameters by their names
                    const lat = searchParams.get("lat")
                    const lon = searchParams.get("lon")
                    const detailLat = searchParams.get("detailLat")
                    const detailLon = searchParams.get("detailLon")

                    return {
                        lat,
                        lon,
                        detailLat,
                        detailLon
                    }
                }

                function createCustomURL({
                    location
                }) {
                    // The base URL
                    const baseUrl = "https://embed.windy.com/embed2.html?&width=650&height=450&zoom=7&level=surface&overlay=wind&product=ecmwf&menu=&message=&marker=&calendar=now&pressure=&type=map&location=coordinates&detail=true&metricWind=km%2Fh&metricTemp=%C2%B0C&radarRange=-1";

                    // Create a URL object with the base URL
                    const urlObj = new URL(baseUrl);
                    urlObj.searchParams.set("lat", location.lat);
                    urlObj.searchParams.set("lon", location.lon);
                    urlObj.searchParams.set("detailLat", location.lat);
                    urlObj.searchParams.set("detailLon", location.lon);
                    // Set parameters
                    // Get the final URL as a string 1
                    const finalUrl = urlObj.toString();

                    return finalUrl;
                }

                function fetchGeolocation(address = "Australia") {
                    const endpointUrl = `https://geocode.maps.co/search?q=${address}`;
                    return new Promise((resolve, reject) => {
                        // Make a GET request to the endpoint
                        fetch(endpointUrl)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                }
                                return response.json(); // Parse the JSON response
                            })
                            .then(data => {
                                // Resolve the promise with the data
                                resolve(data);
                            })
                            .catch(error => {
                                // Reject the promise with the error
                                reject(error);
                            });
                    });
                }

                initiateMAP()
            })
        </script>
    </div>
<?php
    return ob_get_clean();
});
