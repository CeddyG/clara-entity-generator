<?php

namespace CeddyG\ClaraEntityGenerator\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CeddyG\ClaraEntityGenerator\Entity;

class EntityController extends Controller
{
    public function index()
    {
        $aTables = Entity::getTables();
        
        return view(
            'clara-entity::index', 
            [
                'aTables'           => $aTables, 
                'sPageTitle'        => 'Entity', 
                'aGotoOptions'      => Entity::generateGotoSelectOptions($aTables), 
                'aRelationOptions'  => Entity::generateRelationSelectOptions($aTables), 
                'aTypeOptions'      => Entity::generateTypeSelectOptions($aTables), 
                'aGenerators'       => Entity::getGenerators()
            ]
        );
    }
    
    public function store(Request $oRequest)
    {
        Entity::store($oRequest->all());
        
        return redirect('admin');
    }
}
