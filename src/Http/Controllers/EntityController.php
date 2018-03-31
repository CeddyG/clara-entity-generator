<?php

namespace CeddyG\ClaraEntityGenerator\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CeddyG\ClaraEntityGenerator\Entity;

class EntityController extends Controller
{
    public function index()
    {
        $aTables            = Entity::getTables();
        $sPageTitle         = 'Entity';
	    $aGotoOptions       = Entity::generateGotoSelectOptions($aTables);
	    $aRelationOptions   = Entity::generateRelationSelectOptions($aTables);
	    $aGenerators        = Entity::getGenerators();
        
        return view(
            'clara-entity::index', 
            compact(
                'aTables', 
                'sPageTitle', 
                'aGotoOptions', 
                'aRelationOptions', 
                'aGenerators'
            )
        );
    }
    
    public function store(Request $oRequest)
    {
        Entity::store($oRequest->all());
        
        return redirect('admin');
    }
}
