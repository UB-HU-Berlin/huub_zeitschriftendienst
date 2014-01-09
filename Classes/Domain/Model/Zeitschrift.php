<?php

class Tx_HuubZeitschriftendienst_Domain_Model_Zeitschrift extends Tx_Extbase_DomainObject_AbstractEntity {

    /**
     * @var string
     **/
    protected $zeitschrift = '';

    /**
     * @var string
     **/
    protected $zdbid = '';


    public function __construct($zeitschrift = '', $zdbid = '') {
        $this->setZeitschrift($zeitschrift);
        $this->setZdbid($zdbid);
    }
  
    public function setZeitschrift($zeitschrift) {
        $this->zeitschrift = $zeitschrift;
    }
    
    public function setZdbid($zdbid) {
        $this->zdbid = $zdbid;
    }
    
    public function getZeitschrift() {
        return $this->zeitschrift;
    }
    
    public function getZdbid() {
        return $this->zdbid;
    }
    
}

?>
