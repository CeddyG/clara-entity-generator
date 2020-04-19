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
        $aConfig = config('clara.navbar', []);
        $aConfig[$sFolder]  = ucwords(str_replace('-', ' ', $sFolder));
        
        Config::set('clara.navbar', $aConfig);
        
        $sConfig = var_export($aConfig, true);
        $sConfig = str_replace('  array (', '[', $sConfig);
        $sConfig = str_replace('array (', '[', $sConfig);
        $sConfig = str_replace('  [', '[', $sConfig);
        $sConfig = str_replace(')', ']', $sConfig);
        $sConfig = preg_replace('/[0-9] => \'/i', '\'', $sConfig);
        $sConfig = preg_replace('/[0-9] => \n/i', '', $sConfig);
        $sConfig = substr($sConfig, 1);
        $sConfig = substr($sConfig, 0, -1);
        $sConfig = str_replace("  \n", '', $sConfig);
        $sConfig = str_replace('  ', '    ', $sConfig);
        $sConfig = '    '.trim($sConfig);
        
        self::createFile('clara.navbar.php', [
            'Config' => $sConfig
        ]);
    }
}
