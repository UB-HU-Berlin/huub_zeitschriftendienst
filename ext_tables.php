<?php
if (!defined ('TYPO3_MODE')) { 
    die ('Access denied.');
}

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Basic Configuration');


$TCA['tx_huubzeitschriftendienst_domain_model_zeitschrift'] = array (
    'ctrl' => array (
        'title'     => 'LLL:EXT:huub_zeitschriftendienst/locallang_db.xml:tx_huubzeitschriftendienst_domain_model_zeitschrift',        
        'label'     => 'zeitschrift',    
        'tstamp'    => 'tstamp',
        'crdate'    => 'crdate',
        'cruser_id' => 'cruser_id',
        'languageField'            => 'sys_language_uid',    
        'transOrigPointerField'    => 'l10n_parent',    
        'transOrigDiffSourceField' => 'l10n_diffsource',    
        'default_sortby' => 'ORDER BY zeitschrift',    
        'delete' => 'deleted',    
        'enablecolumns' => array (        
            'disabled' => 'hidden',
        ),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
        'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_huubzeitschriftendienst_domain_model_zeitschrift.gif',
    ),
);



Tx_Extbase_Utility_Extension::registerPlugin($_EXTKEY, 'List', 'Der Zeitschriftendienst');
    
    t3lib_extMgm::addLLrefForTCAdescr('_MOD_tools_txschedulerM1', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh_scheduler.xml');
?>