<?php
define ("CREATE_DB", true);
require_once ("lib.php");
$db->debug(true);
if (!$db->setDB($mysqlDatabase)) {
	$db->query("create database tracks");
	if (!$db->setDB($mysqlDatabase)) {
		echo "Failed to create/connect to database ($mysqlDatabase) on server ($mysqlServer)";
		exit;
	} else {
		echo "Created database ($mysqlDatabase) on server ($mysqlServer)";
	}
}
$setup = array(
	"DROP TABLE IF EXISTS geometry_columns",
	"CREATE TABLE geometry_columns (F_TABLE_CATALOG varchar(256) DEFAULT NULL, F_TABLE_SCHEMA varchar(256) DEFAULT NULL, F_TABLE_NAME varchar(256) NOT NULL, F_GEOMETRY_COLUMN varchar(256) NOT NULL, COORD_DIMENSION int(11) DEFAULT NULL, SRID int(11) DEFAULT NULL, TYPE varchar(256) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1",
	"DROP TABLE IF EXISTS spatial_ref_sys",
	"CREATE TABLE spatial_ref_sys (SRID int(11) NOT NULL, AUTH_NAME varchar(256) DEFAULT NULL, AUTH_SRID int(11) DEFAULT NULL, SRTEXT varchar(2048) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1",
	"DROP TABLE IF EXISTS track_tables_info",
	"CREATE TABLE track_tables_info (id int(11) NOT NULL AUTO_INCREMENT, tableName varchar(256) NOT NULL, createDate datetime NOT NULL, owner varchar(256) NOT NULL, userDescr varchar(256) DEFAULT NULL, userPhotoAlbumURL varchar(256) DEFAULT NULL, userHikeLogURL varchar(256) DEFAULT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=latin1",
	"DROP TABLE IF EXISTS track_tags",
	"CREATE TABLE track_tags (id int(11) NOT NULL AUTO_INCREMENT, trackTag varchar(45) NOT NULL, PRIMARY KEY (id), UNIQUE KEY id_UNIQUE (id)) ENGINE=MyISAM DEFAULT CHARSET=latin1",
	"DROP TABLE IF EXISTS track_tags_link",
	"CREATE TABLE track_tags_link (id int(11) NOT NULL AUTO_INCREMENT, trackTagID int(11) NOT NULL, tracksID int(11) NOT NULL, PRIMARY KEY (id), UNIQUE KEY TagTrackLink (trackTagID,tracksID)) ENGINE=MyISAM DEFAULT CHARSET=latin1"
	);
$db->query($setup);
?>
