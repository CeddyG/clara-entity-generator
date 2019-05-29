<?php

namespace CeddyG\ClaraEntityGenerator\Generator;

class EventGenerator extends BaseGenerator
{
    /*
     * Path where to generate the file.
     * 
     * @var string
     */
    static $PATH = '/app/Events/';
    
    /*
     * Stub's name to use to create the file.
     * 
     * @var string
     */
    static $STUB = '';
    
    static $LIST_STUB = [
        'AfterDestroyEvent',
        'AfterStoreEvent',
        'AfterUpdateEvent',
        'BeforeDestroyEvent',
        'BeforeStoreEvent',
        'BeforeUpdateEvent',
    ];

    /**
     * Generate the file.
     * 
     * @return void
     */
    public function generate($sName = '')
    {
        foreach (static::$LIST_STUB as $sStub)
        {
            static::$STUB = 'event/'.$sStub;
            
            self::createFile($sName.'/'.$sStub.'.php', [
                'Namespace' => $sName
            ]);            
        }
    }
    
    
}
