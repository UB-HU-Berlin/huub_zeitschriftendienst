<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Ronald Neumann <ronald-neumann@web.de>
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package huub_zeitschriftendienst
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */



class Tx_HuubZeitschriftendienst_Command_TaskCommandController extends Tx_Extbase_MVC_Controller_CommandController {          

    /**
     * zeitschriftRepository
     *
     * @var Tx_HuubZeitschriftendienst_Domain_Repository_ZeitschriftRepository
     */
    protected $zeitschriftRepository;

    
    /**
     * objectManager
     * 
     * @var Tx_Extbase_Object_ObjectManagerInterface
     * @inject
     */   
    protected $objectManager;
    
    
    /**
     * injectZeitschriftRepository
     *
     * @param Tx_HuubZeitschriftendienst_Domain_Repository_ZeitschriftRepository $zeitschriftRepository
     * @return void
     */
    public function injectZeitschriftRepository(Tx_HuubZeitschriftendienst_Domain_Repository_ZeitschriftRepository $zeitschriftRepository) {
        $this->zeitschriftRepository = $zeitschriftRepository;
    }   
    
    /**
     * injectObjectManager
     *
     * @param Tx_Extbase_Object_ObjectManagerInterface $objectManager
     * @return void
     */
    
    /* Scheinbar erledigt die @inject Annotation das hier. Und es funktioniert sowieso nicht.
    public function injectObjectManager(Tx_Extbase_Object_ObjectManagerInterface $objectManager) {
        $this->objectManager = $objectManager;
    }
    */
    
    /**
     * Hilfsfunktion
     *
     *
    */

    private function getZdbidFromEzb($jourid) {
      
        $url = "http://rzblx1.uni-regensburg.de/ezeit/detail.phtml?client_ip=".$_SERVER['REMOTE_ADDR'] . "&xmloutput=1&jour_id=" . $jourid;  
        $xmlString = file_get_contents($url);
        if ($xmlSting === FALSE) {
            return FALSE;
        }
        $xmlString = simplexml_load_string($xmlString);
    
        return (string) $xmlString->ezb_detail_about_journal->journal->detail->ZDB_number;

    }
    

    /**      
     * Command to update all Zeitschriften      
     *       
     * @param string $zdb Notation für die ZDB
     * @param string $ezb Notation für die EZB
     * @return void      
     */     
    
    public function UpdateZeitschriftenCommand($zdb, $ezb) {         
        $this->zeitschriftRepository->removeAll();
	$tableName = 'tx_huubzeitschriftendienst_domain_model_zeitschrift';
	$dbName = TYPO3_db;
	$con  = mysqli_connect(TYPO3_db_host,TYPO3_db_username,TYPO3_db_password,$dbName);

	$query = "DELETE FROM ".$tableName." WHERE deleted = 1";
//	$result = mysqli_query($con,$query);

////EZB

        //durchlauf (EZB) geht wie folgt:
        //Wir starten, Wenn next_fifity gesetzt, dann Aufruf mit SC = SC un SINDEX += 50.
        //Wenn next_fifty nicht gesetzt, dann Aufruf mit SC = LC und Sindex = 0,

        //baseurl
        $baseurl = "http://rzblx1.uni-regensburg.de/ezeit/fl.phtml?client_ip=".$_SERVER['REMOTE_ADDR'] . "&xmloutput=1&notation=" . $ezb;



        //startwerte
        $sindex = "0";
        $sc = "A"; //aktueller Buchstabe
        $lc = "B"; //nächster Buchstabe
        $i = 1;

        do {

        

            $url = $baseurl . "&sc=" . $sc . "&sindex=" . $sindex;
            $xmlString = file_get_contents($url);

	    if ($xmlString === FALSE) {
                return FALSE;
            }
            $xmlString = simplexml_load_string($xmlString);
            foreach($xmlString->ezb_alphabetical_list->alphabetical_order->journals->journal as $KEY => $VALUE) {
                $zdbid = '';
                $titel = '';
                $titel = trim((string) $VALUE->title);
                $zdbid = $this->getZdbidFromEzb($VALUE["jourid"]);
                
                if ($zdbid === FALSE) {
                    return FALSE;
                }
                /*
        
                $result = mysql_query("INSERT INTO t3_evifa.tx_huubzeitschriftendienst_domain_model_zeitschrift(pid, tstamp, crdate, zeitschrift, zdbid) VALUES ('0', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), '$titel', '$zdbid')", $db);
                if(!$result) {
                    //tx_scheduler::log('Fehler beim EZB-Insert: ' . mysql_error() . '<br>');
                    //return FALSE;
                
                */
                $zeitschrift = $this->objectManager->create('Tx_HuubZeitschriftendienst_Domain_Model_Zeitschrift', $titel, $zdbid);
                $this->zeitschriftRepository->add($zeitschrift);
            

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
    
   $weiter=false; 
        } // do
        while ($weiter);




//ZDB
//changed from
//"http://services.d-nb.de/sru/zdb?version=1.1&operation=searchRetrieve&query=ssg%3D" . $zdb . "&maximumRecords=10&recordSchema=MABxml-1";
//to
//"http://services.dnb.de/sru/zdb?version=1.1&operation=searchRetrieve&query=ssg%3D". $zdb ."&maximumRecords=10&recordSchema=MARC21-xml";
//23.11.2013 
        $baseurl = "http://services.dnb.de/sru/zdb?version=1.1&operation=searchRetrieve&query=ssg%3D". $zdb ."&maximumRecords=10&recordSchema=MARC21-xml";
        $nextRecord = "1";
        do {

            $url = $baseurl . "&startRecord=" . $nextRecord;
            $xmlString = file_get_contents($url);
	    if ($xmlString === FALSE) {
       //         return FALSE;
            }
            $xmlString = simplexml_load_string($xmlString);
            foreach ($xmlString->records->record as $value) {
                $zdbId = '';
                $titel = ''; 

// TEST DRIVE^  
		$nsChildren = $value->recordData->children("slim",TRUE);
//	$rec = $test->xpath("slim:datafield[@tag='245']/slim:subfield");
  $rec = (array)$nsChildren->xpath("slim:datafield[@tag='016']/slim:subfield[. ='DE-600']/../slim:subfield[@code='a'] | slim:datafield[@tag='245']/slim:subfield[@code='a'] | slim:datafield[@tag='245']/slim:subfield[@code='b']");

	(string)$titel = (string)trim($rec[1][0]);
	
	if($rec[2][0]){ 
		$titel .= ": ".(string)trim($rec[2][0]);
	}	
	
	$zdbId =(string)trim($rec[0][0]);		

/*                foreach ( $value->recordData->datensatz->feld as $value) {

                    if ((string) $value['nr'] == "025" && (string) $value['ind'] == "z" ) {
                        $zdbId = (string) $value;

                    }
                    if ((string) $value['nr'] == "331") {
                        $titel = trim((string) $value);
                    }
                } //foreach
  */          
                // $query = "INSERT INTO t3_evifa.tx_huubzeitschriftendienst_domain_model_zeitschrift(pid, tstamp, crdate, zeitschrift, zdbid) VALUES ('0', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), '$titel', '$zdbId')";
                //$result = mysql_query($query, $db);
                //echo("Query: " . $query . "<br>"); 
            
                $zeitschrift = $this->objectManager->create('Tx_HuubZeitschriftendienst_Domain_Model_Zeitschrift', $titel, $zdbId);
                $this->zeitschriftRepository->add($zeitschrift);
           
                   
     //           if(!$result) {
                    //tx_scheduler::log('Fehler beim ZDB-Insert: ' . mysql_error() . '<br>');
                    //return FALSE;
                          //echo("Fehleer<br><br>");
                
            
     //           }
            } //foreach
    
    
            //print_r($xmlString);
    
            $maxRecord = (integer) $xmlString->numberOfRecords;
            $nextRecord = (integer) $xmlString->nextRecordPosition;

    //echo($nextRecord . " < " . $maxRecord);
    
    


    

        } //do
        while  ($nextRecord <= $maxRecord);
        

         
        

        
        
   
    }

}
    
?>
