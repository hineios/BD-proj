<?php
$pg_hostname = "db.ist.utl.pt";
$pg_user = "ist169632";//"ist170227";//
$pg_password = "ztat5885";//"carpooling";//
$pg_port=5432;
$pg_database = $pg_user;
$bd = pg_connect("host=$pg_hostname port=$pg_port user=$pg_user password=$pg_password dbname=$pg_database") or die(pg_last_error());
?>
