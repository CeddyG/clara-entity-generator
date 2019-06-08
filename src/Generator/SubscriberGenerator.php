<?php

namespace CeddyG\ClaraEntityGenerator\Generator;

class SubscriberGenerator extends BaseGenerator
{
    /*
     * Path where to generate the file.
     * 
     * @var string
     */
    static $PATH = '/app/Listeners/';
    
    /*
     * Stub's name to use to create the file.
     * 
     * @var string
     */
    static $STUB = 'Subscriber';
    
    private $sFkField = '';

    /**
     * Generate the file.
     * 
     * @return void
     */
    public function generate($sName = '', $sTable = '', $aInputs = [], $aColumns = '')
    {
        $bIsText = $aInputs['type-'.$sTable] != '0';
        
        if ($bIsText)
        {
            $this->getFkField($aInputs['type-'.$sTable], $aColumns);
        }
        
        self::createFile($sName.'Subscriber.php', [
            'Repository'    => $sName.'Repository',
            'Class'         => $sName.'Subscriber',
            'Request'       => $sName.'Request',
            'Table'         => $sTable,
            'StoreCode'     => $this->getStoreCode($bIsText),
            'DeleteCode'    => $this->getDeleteCode($bIsText),
            'SubscribeCode' => $this->getSubscribeCode($bIsText, $sName.'Subscriber', $aInputs['type-'.$sTable])
            
        ]);
        
        $this->generateConfig('App\Listeners\\'.$sName.'Subscriber');
    }
    
    private function getStoreCode($bIsText)
    {
        return !$bIsText
            ? '//Do what you want'
            : 'foreach ($aInputs as $iIdLang => $aInput)
        {
            $aInput[\'fk_lang\'] = $iIdLang;
            $aInput[\''.$this->sFkField.'\'] = $oEvent->id;
            
            $this->oRepository->updateOrCreate(
                [
                    [\'fk_lang\', \'=\', $iIdLang],
                    [\''.$this->sFkField.'\', \'=\', $oEvent->id]
                ], 
                $aInput
            );
        }';
    }
    
    private function getDeleteCode($bIsText)
    {
        return !$bIsText
            ? '//Delete your item'
            : '$this->oRepository->deleteWhere([[\''.$this->sFkField.'\', \'=\', $oEvent->id]]);';
    }
    
    private function getSubscribeCode($bIsText, $sName, $sFkTable)
    {
        $sNameFk = ucfirst(camel_case($sFkTable));
        
        return !$bIsText
            ? '//Subscribe to event you want'
            : '$oEvent->listen(
            \'App\Events\\'.$sNameFk.'\BeforeStoreEvent\',
            \'App\Listeners\\'.$sName.'@validate\'
        );

        $oEvent->listen(
            \'App\Events\\'.$sNameFk.'\AfterStoreEvent\',
            \'App\Listeners\\'.$sName.'@store\'
        );
        
        $oEvent->listen(
            \'App\Events\\'.$sNameFk.'\BeforeUpdateEvent\',
            \'App\Listeners\\'.$sName.'@validate\'
        );
        
        $oEvent->listen(
            \'App\Events\\'.$sNameFk.'\AfterUpdateEvent\',
            \'App\Listeners\\'.$sName.'@store\'
        );
        
        $oEvent->listen(
            \'App\Events\\'.$sNameFk.'\BeforeDestroyEvent\',
            \'App\Listeners\\'.$sName.'@delete\'
        );';
    }
    
    private function getFkField($sFkTable, $aColumns)
    {
        foreach ($aColumns as $i => $column)
        {
            if (!array_has($column, 'tableFk'))
            {
                $aColumns[$i]['tableFk'] = '';
            }
        }
        
        $iKey = array_search($sFkTable, array_column($aColumns, 'tableFk'));
        
        $this->sFkField = $aColumns[$iKey]['field'];
    }
    
    private function generateConfig($sClass)
    {
        $aConfig = config('clara.subscriber', []);
        $aConfig[] = $sClass;
        
        Config::set('clara.subscriber', $aConfig);
        
        if (!in_array($sClass, $aConfig))
        {
            static::$PATH = '/config/';
            static::$STUB = 'config';

            $aConfig[]  = $sClass;

            $aNewConfig = [];
            foreach ($aConfig as $sKey => $sValue)
            {
                $aNewConfig[] = "    ".$sValue.'::class';
            }

            self::createFile('clara.subscriber.php', [
                'Config' => implode(",\n", $aNewConfig)
            ]);
            
            static::$PATH = '/app/Listeners/';
            static::$STUB = 'Subscriber';
        }
    }
}
