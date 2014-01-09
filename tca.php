<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_huubzeitschriftendienst_domain_model_zeitschrift'] = array (
	'ctrl' => $TCA['tx_huubzeitschriftendienst_domain_model_zeitschrift']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,zeitschrift,zdbid'
	),
	'feInterface' => $TCA['tx_huubzeitschriftendienst_domain_model_zeitschrift']['feInterface'],
	'columns' => array (
		'sys_language_uid' => array (		
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array (
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
				),
				'foreign_table'       => 'tx_huubzeitschriftendienst_domain_model_zeitschrift',
				'foreign_table_where' => 'AND tx_huubzeitschriftendienst_domain_model_zeitschrift.pid=###CURRENT_PID### AND tx_huubzeitschriftendienst_domain_model_zeitschrift.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array (		
			'config' => array (
				'type' => 'passthrough'
			)
		),
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'zeitschrift' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:huub_zeitschriftendienst/locallang_db.xml:tx_huubzeitschriftendienst_domain_model_zeitschrift.zeitschrift',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'zdbid' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:huub_zeitschriftendienst/locallang_db.xml:tx_huubzeitschriftendienst_domain_model_zeitschrift.zdbid',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, zeitschrift, zdbid')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);
?>