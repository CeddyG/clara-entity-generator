<?php

namespace CeddyG\ClaraEntityGenerator\Generator;

class ControllerGenerator extends BaseGenerator
{
    /*
     * Path where to generate the file.
     * 
     * @var string
     */
    static $PATH = '/app/Http/Controllers/Admin/';
    
    /*
     * Stub's name to use to create the file.
     * 
     * @var string
     */
    static $STUB = 'Controller';
    
    /**
     * Generate the file.
     * 
     * @return void
     */
    public function generate($sTable = '', $sName = '', $sFolder = '', $aFiles = [])
    {
        self::createFile($sName.'Controller.php', [
            'Class'         => $sName.'Controller',
            'Event'         => array_key_exists('event', $aFiles) ? $this->getEvent($sName) : '',
            'Repository'    => $sName.'Repository',
            'Request'       => $sName.'Request',
            'Path'          => strtolower($sFolder),
            'Name'          => $sFolder.'.'.$sTable
        ]);
    }
    
    protected function getEvent($sName)
    {
        $sEvent = "\n";
        $sEvent .= '    protected $sEventBeforeStore    = \App\Events\\'.$sName.'\BeforeStoreEvent::class;
    protected $sEventAfterStore     = \App\Events\\'.$sName.'\AfterStoreEvent::class;
    protected $sEventBeforeUpdate   = \App\Events\\'.$sName.'\BeforeUpdateEvent::class;
    protected $sEventAfterUpdate    = \App\Events\\'.$sName.'\AfterUpdateEvent::class;
    protected $sEventBeforeDestroy  = \App\Events\\'.$sName.'\BeforeDestroyEvent::class;
    protected $sEventAfterDestroy   = \App\Events\\'.$sName.'\AfterDestroyEvent::class;';
        $sEvent .= "\n";
        
        return $sEvent;
    }
}
