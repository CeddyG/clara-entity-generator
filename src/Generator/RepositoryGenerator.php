<?php

namespace CeddyG\ClaraEntityGenerator\Generator;

class RepositoryGenerator extends ModelGenerator
{
    /*
     * Path where to generate the file.
     * 
     * @var string
     */
    static $PATH = '/app/Repositories/';
    
    /*
     * Stub's name to use to create the file.
     * 
     * @var string
     */
    static $STUB = 'Repository';
    
    /**
     * Directory for specific stubs.
     * 
     * @var string
     */
    static $STUB_DIR = '/resources/blueprints/repository/';
    
    /**
     * Array to contains all function (belongsTo, belongsToMany and hasMany)
     * 
     * @var array
     */
    protected $aRelation = [];

    /**
     * Generate the file.
     * 
     * @return void
     */
    public function generate($sName = '', $sTable = '', $aColumns = '', $aRelations = '')
    {
        $sId = 'id';
        
        $aField             = $this->buildFields($sId, $aColumns);
        $sForeignFunction   = $this->buildForeignFunction($aRelations);
        $aRelations         = $this->aRelation;
        $this->aRelation    = [];
         
        self::createFile($sName.'Repository.php', [
            'Class'     => $sName.'Repository',
            'Table'     => $sTable,
            'Fillable'  => $aField['fillable'],
            'Id'        => $sId,
            'Timestamp' => $this->getTimestamp($aColumns),
            'Date'      => $aField['date'],
            'Fk'        => $aField['belongsto'].$sForeignFunction,
            'Relation'  => implode(",\n        ", $aRelations)
        ]);
    }
    
    protected function getTimestamp($aColumns)
    {
        $aField = array_column($aColumns, 'field');
        
        return in_array('created_at', $aField) && in_array('updated_at', $aField)
            ? "\n".'    protected $bTimestamp = true;'."\n"
            : '';
    }
    
    protected function getFunctionDate($sField)
    {        
        return '';
    }
    
    public function getFunctionBelongsTo($aColumn)
    {
        $sRepository = ucfirst(camel_case($aColumn['tableFk']));
        
        $sStub = $this->getSpecificStub('belongsto');
        $sStub = str_replace('DummyFunction', $aColumn['tableFk'], $sStub);
        $sStub = str_replace('DummyRepository', $sRepository.'Repository', $sStub);
        $sStub = str_replace('DummyField', $aColumn['field'], $sStub);
        
        $this->aRelation[] = "'".$aColumn['tableFk']."'";
        
        return $sStub;
    }
    
    public function getFunctionBelongsToMany($aRelation)
    {
        $sRepository = ucfirst(camel_case($aRelation['related']));
        
        $sStub = $this->getSpecificStub('belongstomany');
        $sStub = str_replace('DummyFunction', $aRelation['related'], $sStub);
        $sStub = str_replace('DummyRepository', $sRepository.'Repository', $sStub);
        $sStub = str_replace('DummyPivot', $aRelation['pivot'], $sStub);
        $sStub = str_replace('DummyFk', $aRelation['fk'], $sStub);
        $sStub = str_replace('DummyRelatedFk', $aRelation['fk_related'], $sStub);
        
        $this->aRelation[] = "'".$aRelation['related']."'";
        
        return $sStub;
    }
    
    public function getFunctionHasMany($aRelation)
    {
        $sRepository = ucfirst(camel_case($aRelation['related']));
        
        $sStub = $this->getSpecificStub('hasmany');
        $sStub = str_replace('DummyFunction', $aRelation['related'], $sStub);
        $sStub = str_replace('DummyRepository', $sRepository.'Repository', $sStub);
        $sStub = str_replace('DummyField', $aRelation['fk'], $sStub);
        
        $this->aRelation[] = "'".$aRelation['related']."'";
        
        return $sStub;
    }
}
