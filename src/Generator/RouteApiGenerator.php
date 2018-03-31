<?php

namespace CeddyG\ClaraEntityGenerator\Generator;

class RouteApiGenerator extends BaseGenerator
{
    /*
     * Path where to generate the file.
     * 
     * @var string
     */
    static $PATH = '/config/';
    
    /*
     * Stub's name to use to create the file.
     * 
     * @var string
     */
    static $STUB = 'config';
    
    /**
     * Generate the file.
     * 
     * @return void
     */
    public function generate($sName = '', $sFolder = '')
    {
        $aConfig = config('clara.route.api', []) + [$sFolder => $sName];
        
        $aNewConfig = [];
        foreach ($aConfig as $sKey => $sValue)
        {
            $aNewConfig[] = "    '".$sKey."' => '".$sValue."'";
        }
        
        self::createFile('clara.route.api.php', [
            'Config' => implode(",\n", $aNewConfig)
        ]);
    }
}