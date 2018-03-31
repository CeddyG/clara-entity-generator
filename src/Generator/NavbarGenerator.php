<?php

namespace CeddyG\ClaraEntityGenerator\Generator;

use Config;

class NavbarGenerator extends BaseGenerator
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
    public function generate($sFolder = '')
    {
        $aConfig            = config('clara.navbar', []);
        $aConfig[$sFolder]  = ucwords(str_replace('-', ' ', $sFolder));
        
        Config::set('clara.navbar', $aConfig);
        
        $aNewConfig = [];
        foreach ($aConfig as $sKey => $sValue)
        {
            $aNewConfig[] = "    '".$sKey."' => '".$sValue."'";
        }
        
        self::createFile('clara.navbar.php', [
            'Config' => implode(",\n", $aNewConfig)
        ]);
    }
}
