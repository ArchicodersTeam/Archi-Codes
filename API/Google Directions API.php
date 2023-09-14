<?php

add_shortcode('custom_google_map', function () {
    ob_start();
?>
    <div id="cstm-map-wrapper">
        <div class="map-form">
            <div class="input-wrapper">
                <label for="origin">
                    Your location:
                </label>
                <input type="text" id="origin" value="Australia">
            </div>
            <div class="input-wrapper">
                <label for="origin">
                    Destination:
                </label>
                <input type="text" id="destination">
            </div>
            <div class="input-wrapper">
                <label for="mode">
                    Mode
                </label>
                <select id="mode">
                    <option value="driving" selected>
                        Driving
                    </option>
                    <option value="walking">
                        Walking
                    </option>
                    <option value="bicycling">
                        Bicycling
                    </option>
                    <option value="transit">
                        Transit
                    </option>
                </select>
            </div>
        </div>
        <iframe id="cstm-google-map" style="height:440px;width:100%;border:0;" frameborder="0" src="https://www.google.com/maps/embed/v1/directions?origin=Australia&destination=+Australia&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&zoom=4&mode=driving"></iframe>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function initiateMAP() {
                    const origin = document.getElementById('origin')
                    const destination = document.getElementById('destination')
                    const mode = document.getElementById('mode')
                    const map = document.getElementById('cstm-google-map')

                    origin.addEventListener('input', function() {
                        const params = getMapParams(map)
                        map.src = createCustomURL({
                            ...params,
                            origin: this.value
                        })
                    })
                    destination.addEventListener('input', function() {
                        const params = getMapParams(map)
                        map.src = createCustomURL({
                            ...params,
                            destination: this.value
                        })
                    })
                    mode.addEventListener('input', function() {
                        const params = getMapParams(map)
                        map.src = createCustomURL({
                            ...params,
                            mode: this.value
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
                    const origin = searchParams.get("origin")
                    const destination = searchParams.get("destination")
                    const key = searchParams.get("key")
                    const zoom = searchParams.get("zoom")
                    const mode = searchParams.get("mode")

                    return {
                        origin,
                        destination,
                        key,
                        zoom,
                        mode
                    }
                }

                function createCustomURL({
                    origin = " ",
                    destination = " ",
                    key,
                    zoom = "10",
                    mode = "driving"
                }) {
                    // The base URL
                    const baseUrl = "https://www.google.com/maps/embed/v1/directions";

                    // Create a URL object with the base URL
                    const urlObj = new URL(baseUrl);

                    // Set parameters
                    console.log(origin)
                    urlObj.searchParams.set("origin", origin == "" ? "Australia" : origin);
                    urlObj.searchParams.set("destination", destination == "" ? "Australia" : destination);
                    urlObj.searchParams.set("key", "AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8"); // Replace with your API key
                    urlObj.searchParams.set("zoom", '13');
                    urlObj.searchParams.set("mode", mode);

                    // Get the final URL as a string 1
                    const finalUrl = urlObj.toString();

                    return finalUrl;
                }

                initiateMAP()
            })
        </script>
    </div>
<?php
    return ob_get_clean();
});
