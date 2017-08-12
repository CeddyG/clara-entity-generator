<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Clara\Generator\EntityService as Entity;

class EntityController extends Controller
{
    public function index()
    {
        $aTables            = Entity::getTables();
        $sPageTitle         = 'Entity';
	    $aGotoOptions       = Entity::generateGotoSelectOptions($aTables);
	    $aRelationOptions   = Entity::generateRelationSelectOptions($aTables);
        
        return view('admin.entity.index', compact('aTables', 'sPageTitle', 'aGotoOptions', 'aRelationOptions'));
    }
    
    public function store(Request $oRequest)
    {
        Entity::store($oRequest->all());
        
        return redirect('admin');
    }
}
