<?php
    
/**
 * Description of TestCommandController
 */
class Tx_HuubZeitschriftendienst_Command_TestCommandController extends Tx_Extbase_MVC_Controller_CommandController {
    
    /**
     * Simple Hello World Command
     * 
     * @param string $name 
     * @return void
     */
    public function TestCommand($name) {
        echo 'Hello ' . $name;
    }
}
    
    
    ?>
