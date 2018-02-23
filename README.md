# Weather-Webservice
A web service that returns current weather information for a mobile client user based on their current location.

# About
#### Requirements:
<ul>
  <li>Capture the latitude/longitude coordinates from the mobile application request.</li>
  <li>Consume weather data from https://openweathermap.org/.</li>
  <li>Extract the weather details for the client's given location and return the following values in JSON format:
  <ul>
    <li>Temperature in Fahrenheit</li>
    <li>Weather, e.g. "clear", "overcast"</li>
    <li>Wind speed</li>
    <li>Wind direction</li>
  </ul> </li>
  <li>Needs to support multiple versions of the mobile application since not all customers upgrade immediately, so backwards-compatibility is key.</li>
</ul>

#### Considerations:
<ul>
  <li>Business could agree to obtain their weather data from a different source, so design your solution to minimize code rework.</li>
  <li>The support team heavily relies on logging to troubleshoot and diagnose production issues.</li>
</ul>


# Installation Instruction
<ol>
  <li>Download Zip or clone repository.</li>
  <li>Put Repository inside htdocs folder of apache or xampp</li>
  <li>Create .local folder in project directory.</li>
  <li>Create authorization.php (to authorize user) and config-local.php (to define constants and initialize logging) files in .local folder.</li>
  <li>Click <a href="http://localhost/weather-webservice/testHtml.html">here</a> and test the service.</li>
</ol>
