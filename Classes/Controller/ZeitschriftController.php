<?php


class Tx_HuubZeitschriftendienst_Controller_ZeitschriftController extends Tx_Extbase_MVC_Controller_ActionController {

    /**
     * @param string $letter Buchstabe
     * @return
     */

    public function listAction($letter = 'A') {
        $zeitschriftRepository = t3lib_div::makeInstance('Tx_HuubZeitschriftendienst_Domain_Repository_ZeitschriftRepository');
        $zeitschriften = $zeitschriftRepository->findByLetter($letter);
        $alleZeitschriften = $zeitschriftRepository->findAll();
        
        $this->view->assign('zeitschriften', $zeitschriften);
        $this->view->assign('alleZeitschriften', $alleZeitschriften);
        

    }

    /**
     * @param string $search Suchstring
     * @return 
     */
    public function searchAction($searchString) {
        $zeitschriftRepository = t3lib_div::makeInstance('Tx_HuubZeitschriftendienst_Domain_Repository_ZeitschriftRepository');
        $zeitschriften = $zeitschriftRepository->findBySearch($searchString);
        $alleZeitschriften = $zeitschriftRepository->findAll();

        $this->view->assign('suchString', $searchString);
        $this->view->assign('zeitschriften', $zeitschriften);
        $this->view->assign('alleZeitschriften', $alleZeitschriften);
    }

}



?>