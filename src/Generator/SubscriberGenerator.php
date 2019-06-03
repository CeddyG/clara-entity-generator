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

    /**
     * Generate the file.
     * 
     * @return void
     */
    public function generate($sName = '', $sTable = '')
    {
        self::createFile($sName.'Subscriber.php', [
            'Repository'    => $sName.'Repository',
            'Class'         => $sName.'Subscriber',
            'Request'       => $sName.'Request',
            'Table'         => $sTable
        ]);
    }
    
    
}
