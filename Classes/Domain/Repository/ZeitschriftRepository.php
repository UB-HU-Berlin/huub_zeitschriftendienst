<?php

class Tx_HuubZeitschriftendienst_Domain_Repository_ZeitschriftRepository extends Tx_Extbase_Persistence_Repository {

    public function findByLetter($letter) {
        $query = $this->createQuery();
        
        if ($letter == '#') {
            $query->matching(
                $query->logicalNot(
                    $query->logicalOr(array(
                        $query->like('zeitschrift', 'A%'),
                        $query->like('zeitschrift', 'B%'),
                        $query->like('zeitschrift', 'C%'),
                        $query->like('zeitschrift', 'D%'),
                        $query->like('zeitschrift', 'E%'),
                        $query->like('zeitschrift', 'F%'),
                        $query->like('zeitschrift', 'G%'),
                        $query->like('zeitschrift', 'H%'),
                        $query->like('zeitschrift', 'I%'),
                        $query->like('zeitschrift', 'J%'),
                        $query->like('zeitschrift', 'K%'),
                        $query->like('zeitschrift', 'L%'),
                        $query->like('zeitschrift', 'M%'),
                        $query->like('zeitschrift', 'N%'),
                        $query->like('zeitschrift', 'O%'),
                        $query->like('zeitschrift', 'P%'),
                        $query->like('zeitschrift', 'Q%'),
                        $query->like('zeitschrift', 'R%'),
                        $query->like('zeitschrift', 'S%'),
                        $query->like('zeitschrift', 'T%'),
                        $query->like('zeitschrift', 'U%'),
                        $query->like('zeitschrift', 'V%'),
                        $query->like('zeitschrift', 'W%'),
                        $query->like('zeitschrift', 'X%'),
                        $query->like('zeitschrift', 'Y%'),
                        $query->like('zeitschrift', 'Z%')
                        )
                                       )
                    )
                );
                                       

            
                               
                
                
                
            //  'SELECT * FROM tx_huubzeitschriftendienst_domain_model_zeitschrift WHERE LEFT(zeitschrift,1) NOT IN ? ORDER BY zeitschrift ASC', array(array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z')));
        }
        else {            
            $query->matching($query->like('zeitschrift', $letter . '%'));
            $query->setOrderings(array('zeitschrift' => Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING));
        }
        
        return $query->execute();
    }

    public function findBySearch($search) {
        $query = $this->createQuery();
        $query->matching($query->like('zeitschrift', '%' . $search . '%'));
        $query->setOrderings(array('zeitschrift' => Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING));
        return $query->execute();
    }
}
?>