<html lang="pl">
<head>
<meta http-equiv="Refresh" content="100" />
<title>Zdunowski</title>
</head>


<body>
<div>Lista adresów IP, która odwiedzila strone.</div><br>
<a href="http://www.lukasz-zdunowski.com.pl/lab2/" >Powrót.</a><br><br>

<?php
        $dbhost="lukasz-zdunowski.com.pl"; 
        $dbuser="25509958_lab1"; 
        $dbpassword="zaq12wsx";  
        $dbname="25509958_lab1";
        $polaczenie = mysqli_connect ($dbhost, $dbuser, $dbpassword);
        mysqli_select_db($polaczenie, $dbname);
        
        $rezultat = mysqli_query ($polaczenie, "SELECT * FROM goscie ORDER BY ilewejsc DESC");

        if ($polaczenie->connect_error) {
        die("Connection failed: " . $polaczenie->connect_error);
    } 
        print "<TABLE CELLPADDING=5 BORDER=1>";
            print "<TR><TD>idt</TD><TD>Adres</TD><TD>Data</TD><TD>City</TD><TD>Country</TD><TD>Region</TD><TD>Lication</TD><TD>Link to Google</TD><TD>Visits</TD></TR>\n";

        $ipaddress = $_SERVER["REMOTE_ADDR"];
        function ip_details($ip) {
        $json = file_get_contents ("http://ipinfo.io/{$ip}/geo");
        $details = json_decode ($json);
        return $details;
        }
        $details = ip_details($ipaddress);
        $region = $details -> region;
        $country =  $details -> country;
        $city = $details -> city; 
        $loc = $details -> loc;
        $ip = $details -> ip; 
        
        $czas2 = date("F j, Y, g:i a"); 

        $result = mysqli_query($polaczenie,  "SELECT adres FROM goscie WHERE adres='$ip' ");   //czy row Z danym IP jest juz w bazie danych
        $row_cnt = mysqli_num_rows($result);
      
        if($row_cnt >= 1){          // echo "   --takie adres jest juz w bazie--         ";


         //   $adres_bd = mysqli_query($polaczenie,  "UPDATE goscie SET adres=('$ip')");
            $czas_bd = mysqli_query($polaczenie,  "UPDATE  goscie SET data=('$czas2') WHERE adres='$ip' "); 
            $city_bd = mysqli_query($polaczenie,  "UPDATE  goscie SET city=('$city') WHERE adres='$ip' "); 
            $country_bd = mysqli_query($polaczenie,  "UPDATE  goscie SET country=('$country')WHERE adres='$ip'");
            $region_bd = mysqli_query($polaczenie,  "UPDATE  goscie SET region=('$region') WHERE adres='$ip'"); 
            $loc_bd = mysqli_query($polaczenie,  "UPDATE  goscie SET loc=('$loc') WHERE adres='$ip'");
            $link_bd = mysqli_query($polaczenie,  "UPDATE  goscie SET link=('$linkToMaps')  WHERE adres='$ip'");

            $attempts2 =  mysqli_query($polaczenie, "UPDATE goscie SET ilewejsc=ilewejsc+1 WHERE adres='$ip'");    // ilosc wejsc ++
            $attempts2 = mysqli_query($polaczenie,  "SELECT ilewejsc FROM goscie WHERE adres=('$ip')");            //pobranie z BD ilosci wejsc

            if ($row = $attempts2->fetch_assoc()) {                                                                 // attemts2 jako string
                $ilewejsc1= $row['ilewejsc']."<br>";}


        }else{
        // echo "    --nowy adres--      ";
        $doBazy =  "INSERT INTO goscie (adres, data, city, country, region, loc) VALUES ('$ip', '$czas2','$city','$country', '$region', '$loc')";
        $attempts2 =  mysqli_query($polaczenie, "UPDATE goscie SET ilewejsc=ilewejsc+1 WHERE adres='$ip'");         // ilosc wejsc 



       if ($polaczenie->query($doBazy) === TRUE) {
         }   else {
            echo "Error: " . $doBazy . "<br>" . $polaczenie->error;
          }
        }
        if ($polaczenie->connect_error) {
            die("Connection failed: " . $polaczenie->connect_error);
        } 
        //    echo $sortby;

    while ($wiersz = mysqli_fetch_array ($rezultat))
     {      
            $idt1 = $wiersz ['id'];
            $adres1 = $wiersz['adres'];
            $czass1 = $wiersz['data']; 
            $region1 = $wiersz['region'];
            $country1 = $wiersz['country'];
            $city1 = $wiersz['city'];
            $loc1 = $wiersz['loc'];
          //$gMapss = $wiersz['link'];
            $liczbaWejsc = $wiersz['ilewejsc'];

            $ad = mysqli_query($polaczenie,  "SELECT loc FROM goscie WHERE adres='$adres1'");
            if ($row = $ad->fetch_assoc()) {                                                                     
                     $XYdoMap= $row['loc'];
                    }


            $google = 'http://maps.google.co.in/maps?q='.$XYdoMap;    
            $location = '<a href='.$google.'>Google Maps</a>';    

        print "<TR><TD>$idt1</TD><TD>$adres1</TD><TD>$czass1</TD><TD>$city1</TD><TD>$country1</TD><TD>$region1</TD><TD>$loc1</TD><TD>$location</TD><TD>$liczbaWejsc</TD></TR>\n";
            
       
            }
        print "</TABLE>";

?>
</body>
</html>