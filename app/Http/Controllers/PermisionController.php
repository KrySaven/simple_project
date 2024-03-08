<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Permission;
use Session;


class PermisionController extends Controller
{
    //


    public function savePermision(Request $request)
    {
    	$permisions = $request->permision;
    	$user_group = $request->user_group;

    	

		try {

			DB::beginTransaction();

		   Permission::where('user_group_id',$user_group)->delete();
		   for($i=0;$i<=count($permisions)-1;$i++)
	    	{

	    		

	    		if (strpos($permisions[$i], '||')) 
	    		{
				    $new_permision = explode("||", $permisions[$i]);
				    foreach($new_permision as $row)
				    {
				    	Permission::create([
			    			'user_group_id' => $user_group,
			    			'name' => $row

			    		]);
				    }
				}
				else
				{
					Permission::create([
		    			'user_group_id' => $user_group,
		    			'name' => $permisions[$i]

		    		]);
				}


	    		
	    	}


		    DB::commit();

		    Session::flash('success','Permision saved successfully.');
		    $notification = array(
                'message' => "Permision saved successfully!",
                'alert-type' => 'success'
            );

		} catch (\Exception $e) {
		    DB::rollback();
		    echo "something wrong";die();
		}
		    	

    	return redirect()->route('usergroups')->with($notification);

    }
}
