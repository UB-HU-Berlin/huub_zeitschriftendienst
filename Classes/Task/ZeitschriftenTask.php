<?php

class Tx_HuubZeitschriftendienst_Task_ZeitschriftenTask extends tx_scheduler_Task 
{

    private function getZdbidFromEzb($jourid) {
        require_once("/var/www/classes/xmlfile.php");

        $url = "http://rzblx1.uni-regensburg.de/ezeit/detail.phtml?client_ip=".$_SERVER['REMOTE_ADDR'] . "&xmloutput=1&jour_id=" . $jourid;  
        $xmlString = file_get_contents($url);
        if ($xmlSting === FALSE) {
            return FALSE;
        }
        $xmlString = simplexml_load_string($xmlString);
    
        return (string) $xmlString->ezb_detail_about_journal->journal->detail->ZDB_number;

    } // function  getZdbidFromEzb
    

    public function execute() 
    {

        //$db= mysql_connect('localhost', 'evifa', 'ethno23');
        $db= mysql_connect('localhost', 'admin', 'e2v*i4f=a8');

        if(!$db) {
            echo('Fehler beim Datenbankconnect: ' . mysql_error() . '<br>');
            tx_scheduler::log('Fehler beim Datenbankconnect: ' . mysql_error(), 1, 'scheduler');
            return FALSE; 
        }



        require_once("/var/www/classes/xmlfile.php");

        $result = mysql_query('TRUNCATE TABLE t3_evifa.tx_huubzeitschriftendienst_domain_model_zeitschrift', $db);

        if(!$result) {
            tx_scheduler::log('Fehler beim Leeren: ' . mysql_error() . '<br>');
            return FALSE;
            
        } //if




////EZB

        //durchlauf (EZB) geht wie folgt: 
        //Wir starten, Wenn next_fifity gesetzt, dann Aufruf mit SC = SC un SINDEX += 50.
        //Wenn next_fifty nicht gesetzt, dann Aufruf mit SC = LC und Sindex = 0,

        //baseurl
        $baseurl = "http://rzblx1.uni-regensburg.de/ezeit/fl.phtml?client_ip=".$_SERVER['REMOTE_ADDR'] . "&xmloutput=1&notation=" . $this->suchStringEzb;



        //startwerte 
        $sindex = "0";
        $sc = "A"; //aktueller Buchstabe
        $lc = "B"; //nächster Buchstabe
        $i = 1;

        do {

        

            $url = $baseurl . "&sc=" . $sc . "&sindex=" . $sindex;;
            $xmlString = file_get_contents($url);
            if ($xmlString === FALSE) {
                return FALSE;
            }
            $xmlString = simplexml_load_string($xmlString);
    
            foreach($xmlString->ezb_alphabetical_list->alphabetical_order->journals->journal as $KEY => $VALUE) {
                $zdbId = '';
                $titel = '';
                $titel = trim((string) $VALUE->title);
                $zdbid = $this->getZdbidFromEzb($VALUE["jourid"]);
                
                if ($zdbid === FALSE) {
                    return FALSE;
                }
        
                $result = mysql_query("INSERT INTO t3_evifa.tx_huubzeitschriftendienst_domain_model_zeitschrift(pid, tstamp, crdate, zeitschrift, zdbid) VALUES ('0', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), '$titel', '$zdbid')", $db);
                if(!$result) {
                    //tx_scheduler::log('Fehler beim EZB-Insert: ' . mysql_error() . '<br>');
                    //return FALSE;
            
                } //if
        //$href=
        //echo("<a href=\"$href\" target=\"_blank\">");
        //echo((string) $VALUE->title);
        //$src = "http://services.d-nb.de/fize-service/gvr/icon?sid=vifa:evifa&genre=journal&pid=" . urlencode("client_ip=" . $_SERVER['REMOTE_ADDR'] . "&zdbid=" . getZdbidFromEzb($VALUE["jourid"]));
        //echo("<img src=\"$src\">");
        //echo("</a>";
        //echo("<br>");
        //$i++; 
            } //foreach
    
            $sindex_temp = (string) $xmlString->ezb_alphabetical_list->next_fifty["sindex"];
            $lc_temp = (string)$xmlString->page_vars->lc["value"];
            $sc_temp = (string)$xmlString->page_vars->sc["value"]; 
    
    
            $weiter = false;
    
    

                
            if ($sindex_temp != ""){
                $sindex = $sindex_temp;
                $sc = $sc_temp;
                $weiter = true;
            }
            elseif ($lc_temp != "") {
                $sindex = "0";
                $sc = $lc_temp;
                $weiter = true;
            }
    
        } // do
        while ($weiter); 




//ZDB
//changed from "http://services.d-nb.de/sru/zdb?version=1.1&operation=searchRetrieve&query=ssg%3D" . $this->suchStringZdb . "&maximumRecords=10&recordSchema=MABxml-1"; 
//to
//"http://services.dnb.de/sru/zdb?version=1.1&operation=searchRetrieve&query=ssg%3D" . $this->suchStringZdb . "&maximumRecords=10&recordSchema=MARC21-xml"; 
//23.11.2013 
        $baseurl = "http://services.d-nb.de/sru/zdb?version=1.1&operation=searchRetrieve&query=ssg%3D" . $this->suchStringZdb . "&maximumRecords=10&recordSchema=MARC21-xml";
        $nextRecord = "1";
        do {

            $url = $baseurl . "&startRecord=" . $nextRecord;
            $xmlString = file_get_contents($url);
            if ($xmlString === FALSE) {
                return FALSE;
            }
    //$xmlFile=explode("\n",$xmlString);
    //print_r($xmlString);
    
    //<feld nr="025" ind="z">2595035-6</feld>
    //$pattern = '/(.*)(<feld nr=\"025\" ind=\"z\">)(\d*-\d)(<\/feld>)(.*)/';
    //$replace = '$3';
    //echo("<br><br>" . preg_replace($pattern, $replace, $xmlString));
    

    
            $xmlString = simplexml_load_string($xmlString);

    
            foreach ($xmlString->records->record as $value) {
                $zdbId = '';
                $titel = ''; 


// TEST DRIVE
   
                foreach ( $value->recordData->datensatz->feld as $value) {
        

                    
                    if ((string) $value['nr'] == "025" && (string) $value['ind'] == "z" ) {
                        $zdbId = (string) $value;

                        

                    }
                    if ((string) $value['nr'] == "331") {
                        $titel = trim((string) $value);

                      
                    }
                    
            
            
                } //foreach
                //echo("ZDB-ID: " . $zdbId . "<br>");
                //echo("Titel: " . $titel . "<br>");      
//23.11.2013
                $query = "INSERT INTO t3_evifa.tx_huubzeitschriftendienst_domain_model_zeitschrift(pid, tstamp, crdate, zeitschrift, zdbid) VALUES ('0', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), '".$titel."', '".$zdbId."')";
                $result = mysql_query($query, $db);
                //echo("Query: " . $query . "<br>");  
                if(!$result) {
                    //tx_scheduler::log('Fehler beim ZDB-Insert: ' . mysql_error() . '<br>');
                    //return FALSE;
                          //echo("Fehleer<br><br>");
                
            
                }
            } //foreach
    
    
            //print_r($xmlString);
    
            $maxRecord = (integer) $xmlString->numberOfRecords;
            $nextRecord = (integer) $xmlString->nextRecordPosition;
    
    //echo($nextRecord . " < " . $maxRecord);
    
    


    

        } //do
        while  ($nextRecord <= $maxRecord);

        
        return TRUE; // 
    } //function execute
    

} //class
/*
$Test = new Tx_HuubZeitschriftendienst_Task_ZeitschriftenTask;

if ($Test->execute()) {
    echo("erfolg");
}
else {
    echo("shit");
}
*/

?>
