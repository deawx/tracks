Tracks
======
Home-grown Google maps GPS track overlyer using PHP and MySQL with geospatial tables.

Pre-Reqs
-------
### GDAL
GDAL ([http://www.gdal.org/](http://www.gdal.org/)) is the magic; it converts gpx files to MySQL geospatial tables. It's requred. If you use a Red Hat like Linux distrib, just `yum install gdal`. If you use something else... it should be pretty easy to install. Make note of the path to `ogr2ogr`; you'll need to include it in the tracks.ini file.

### Auth
Out of the box, this application relies up Google Authentator. To ease deployment, a nifty class by Michael Kliewe (http://www.phpgangsta.de/) is used. If you don't want to use Google Authenticator, hack the code. It's pretty easy to do. To get things rolling, run the php script 'genTOTP.php', answer the question, and set up the new account. Make note of the secret key; you'll need to include it in the tracks.ini file.

### Google API
You'll need a google API key which allows access to maps. If you don't already have one, [start here](https://developers.google.com/maps/documentation/javascript/v2/introduction#Obtaining_Key). If you don't need the user guide, [go here](https://code.google.com/apis/console) and set up a new API key with access for *Google Maps API v3* (most recent, at the time of this writing).

### GNUPlot
GNUPlot is used to generate elevation plots, on the fly. If you don't care about elevation plots, skip this part. Otherwsie, install gnuplot and make sure it works.

### MyTopo
[MyTopo](http://www.mytopo.com/) provided the topo overlay. [Go here](http://www.mytopo.com/partners/linking.cfm) to establish a partner account. They will provide a partner ID and secret key. Add both to the ini file.

### Tracks.ini
Edit the ini file "tracks.ini"; set sane values.

Setup
-----
Assuming the proper MySQL permissions are granted to allow the creation of a database, you should be able to browse to the index.php file; the script will detect le lack of a proper database and auto-setup. Log in, and import some gpx files.
