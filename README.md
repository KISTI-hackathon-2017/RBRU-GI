# RBRU-GI-KISTI-2017
Direct integrating KISTI database into GIS software using OGC’s WFS standard

## Overview:


## Objectives:
1. To support OGC’s WFS requests and responses
2. To extend the services to GIS users

## Tools and data:
* KISTI’s dataset
* PHP, JavaScript
* GIS software, i.e., QGIS (http://www.qgis.org)
* QGIS WFS 2.0 Client (https://plugins.qgis.org/plugins/wfsclient/)

## Installation
* Copy files to your target directory, i.e., /WWW_ROOT/wfs/.
* In a web browser, type the following request in the address bar and you should see the returned XML document similary to the following figure.
![alt text](https://github.com/KISTI-hackathon-2017/RBRU-GI/blob/master/images/installation_test.png "GetCapabilities results")

## Using the library in QGIS
* Install QGIS WFS 2.0 Client and set the default URL as shown below
* Request GetCapabilities by pressing the *List FeatureTypes* button
* Choose one of the available FeatureTypes

## Example application 1 : Air pollution
![alt text](https://github.com/KISTI-hackathon-2017/RBRU-GI/blob/master/images/qgis_pm25_blended_with_google_satellites.png "PM 2.5")

## Example application 2 : Analyzing taxi movement states
Analyzing texi's speeds and timestamps to find the locations where a taxi was stopped. The following figure illustrates the locations (red dots) where the taxi *IK1033* stopped for more that *120* seconds. The yellow lables presented the time durations (seconds) before the taxi started moving again.
![alt text](https://github.com/KISTI-hackathon-2017/RBRU-GI/blob/master/images/qgis_state_IK1033_with_osm.png "IK1033 states")
