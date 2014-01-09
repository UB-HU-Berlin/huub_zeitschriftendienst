<?php
if (!defined ('TYPO3_MODE')) {
     die ('Access denied.');
}


Tx_Extbase_Utility_Extension::configurePlugin(
    $_EXTKEY, 
    'List',     //Plugin Name
    array(        //Controller-Action Kombinationen, die erlaubt sind.
        'Zeitschrift' => 'list, search'
    ),
    array(        //Controller-Action Kombinationen, deren Ergebnis nicht geacachet werden sollen.
        'Zeitschrift' => 'list, search' 
    )
);


######Fuer die TASK

    
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Tx_HuubZeitschriftendienst_Command_TaskCommandController';
?>
