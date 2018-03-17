<?php

namespace CeddyG\ClaraEntityGenerator\app\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Clara\Generator\EntityService as Entity;

class GenerateEntityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entity:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate CRUD from database\'s table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $aTables = Entity::getTables();
        $aActions = ['routeweb', 'routeapi', 'migration', 'form', 'index', 'model', 'request', 'controller', 'repository', 'traduction'];

        $sSelectionnedTable = $this->choice('Choose from wich table you would like to generate files', array_keys($aTables));
        $sSelectedRelation = 0;

        if (!empty($aTables[$sSelectionnedTable]))
        {
            $aRelations = ['standard'];

            foreach ($aTables[$sSelectionnedTable] as $sPivot => $aRelats)
            {
                foreach ($aRelats as $sRelationTable => $sColumns)
                    $aRelations['Relation with '.$sRelationTable.' via '.$sPivot] = $sColumns;
            }

            $sSelectedRelation = $this->choice('What relation you want', array_keys($aRelations), 0);
        }

        $aSelectionnedActions = $this->choice('What you want to generate', $aActions, null, null, true);

        $aTableActions = [];
        foreach ($aSelectionnedActions as $sAction)
        {
            $aTableActions[$sAction] = 1;
        }

        $aInputs = [
            'table' => [
                $sSelectionnedTable => $aTableActions
            ]
        ];

        if (!empty($sSelectedRelation))
        {
            $aInputs['related-'.$sSelectionnedTable] = [$aRelations[$sSelectedRelation]];
        }

        Entity::store($aInputs);
    }
}
