<?php

namespace CeddyG\ClaraEntityGenerator;

use Session;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class Entity
{
    private static $aDontTouchTables = [
        'dataflow',
        'activations',
        'migrations',
        'persistences',
        'reminders',
        'revisions',
        'roles',
        'role_users',
        'throttle',
        'users'
    ];
    
    public static function getTables()
    {
		$oSchemaManager = self::getSchemaManager();
        $oTablesList    = $oSchemaManager->listTables();
        
        $aTablesName        = self::getTablesName($oTablesList);
        $aTablesRelations   = self::getTablesRelation($oTablesList, $oSchemaManager);
        
        return self::mergeRelationTables($aTablesName, $aTablesRelations);
    }
    
    public static function getGenerators()
    {
        $aConfigs = config('clara.entity.generators');
        
        $aReturn = [];
        foreach ($aConfigs as $aConfig)
        {
            $aReturn[$aConfig['name']] = $aConfig['label'];
        }
        
        return $aReturn;
    }
    
    private static function getSchemaManager()
    {
        $oConfig = new Configuration();
        $aConnectionParams = [
            'driver'    => 'pdo_mysql',
            'host'      => env('DB_HOST'),
            'dbname'    => env('DB_DATABASE'),
            'user'      => env('DB_USERNAME'),
            'password'  => env('DB_PASSWORD')
        ];
        
        $oDatabaseConnection = DriverManager::getConnection($aConnectionParams, $oConfig);
        $oDatabaseConnection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
		
        return $oDatabaseConnection->getSchemaManager();
    }
    
    private static function getTablesName($oTablesList)
    {
        //Create an array with table name
        $aTablesName = [];
        foreach($oTablesList as $oTable)
        {
            if (!in_array($oTable->getName(), self::$aDontTouchTables))
            {
                $aTablesName[] = $oTable->getName();
            }
        }
        
        return $aTablesName;
    }
    
    private static function getTablesRelation($oTablesList, $oSchemaManager)
    {
        //Create an array with tables relations
        $aTablesRelations = [];
        foreach($oTablesList as $oTable)
        {
            $oRelations = $oSchemaManager->listTableForeignKeys($oTable->getName());
            foreach($oRelations as $oRelation)
            {
                $aTablesRelations[] = [
                    'table' => $oTable->getName(),
                    'column' => $oRelation->getLocalColumns()[0],
                    'related' => $oRelation->getForeignTableName()
                ];
            }
        }
        
        return $aTablesRelations;
    }
    
    private static function mergeRelationTables($aTablesName, $aTablesRelations)
    {
        $aTables = [];
        foreach($aTablesName as $aTable)
        {
            $aTables[$aTable] = self::addRelationToTable($aTable, $aTablesRelations);
        }
        
        return $aTables;
    }
    
    private static function addRelationToTable($aTable, $aTablesRelations)
    {
        $aRelations = [];
        foreach($aTablesRelations as $aRelation)
        {
            if($aRelation['related'] == $aTable)
            {
                $aRelations[$aRelation['table']] = self::addRelatedTables($aTable, $aRelation, $aTablesRelations);
            }
        }
        
        return $aRelations;
    }
    
    private static function addRelatedTables($aTable, $aRelation, $aTablesRelations)
    {
        $aRelated = [];
        $aKeys = array_keys(array_column($aTablesRelations, 'table'), $aRelation['table']);

        foreach($aKeys as $iKey)
        {
            if($aTablesRelations[$iKey]['related'] != $aTable)
            {
                $aRelated[$aTablesRelations[$iKey]['related']] = $aTablesRelations[$iKey]['related']
                    .'-'.$aRelation['table']
                    .'-'.$aRelation['column']
                    .'-'.$aTablesRelations[$iKey]['column'];
            }
        }
        
        return $aRelated;
    }
    
    public static function store($aInputs)
    {
        $oGenerator = app('clara.entity.generator');
        
        foreach($aInputs['table'] as $sTable => $aFiles)
        {
            $sName      = ucfirst(camel_case($sTable));
            $sFolder    = str_replace('_', '-', $sTable);
            
            $aMany = self::setPivotRelationTable($sTable, $aInputs);
            
            $oGenerator->generate($sName, $sTable, $sFolder, $aMany, $aFiles, $aInputs);       
        }
        
        Session::flash('success', __('clara-entity::entity.file_generated', ['nb' => $oGenerator->getNbFiles()]));
    }
    
    private static function setPivotRelationTable($sTable, $aInputs)
    {
        $aMany = [];
        if(isset($aInputs['related-'.$sTable]))
        {
            foreach($aInputs['related-'.$sTable] as $sRelation)
            {
                if($sRelation != '0')
                {
                    $aRelation = explode('-', $sRelation);
                    $aMany[] = [
                        'related'               => $aRelation[0],
                        'pivot'                 => $aRelation[1],
                        'foreign_key'           => $aRelation[2],
                        'related_foreign_key'   => $aRelation[3]
                    ];
                }
            }
        }
        
        return $aMany;
    }
    
    public static function generateGotoSelectOptions($aTables)
    {
    	$aOptions = ['' => __('clara-entity::entity.choose_a_table')];
	    foreach($aTables as $sTable => $null)
        {
		    $aOptions['box-'.$sTable] = $sTable;
	    }
	    
	    return $aOptions;
    }
	
	public static function generateRelationSelectOptions($aTables)
    {
		$aOptions = [];
		foreach($aTables as $sTable => $aRelations)
        {
			$aOptions[$sTable] = ['0' => __('clara-entity::entity.standard_relation')];
            
			foreach($aRelations as $sRelation => $aRelatedTabs)
            {
				foreach($aRelatedTabs as $sRelated => $sValue)
                {
					$aOptions[$sTable][$sValue] = __('clara-entity::entity.related_with', ['table' => $sRelated]);
				}
			}
		}
		
		return $aOptions;
	}
	
	public static function generateTypeSelectOptions($aTables)
    {
		$aOptions = [];
		foreach($aTables as $sTable => $aRelations)
        {
			$aOptions[$sTable] = ['0' => __('clara-entity::entity.standard_relation')];
		}
        
		foreach($aTables as $sTable => $aRelations)
        {
			foreach($aRelations as $sRelation => $aRelatedTabs)
            {
				$aOptions[$sRelation][$sTable] = __('clara-entity::entity.text_for', ['table' => $sTable]);
			}
		}
		
		return $aOptions;
	}
}
