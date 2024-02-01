<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Response;


class ItemApiController extends Controller
{
    public function index(Request $request)
    {
        $artTypes = Item::distinct('artType')->pluck('artType');
         
        // Retrieve the current user
        $items = Item::with('user')->paginate(3);
        $items->withQueryString();
        return Response::json([
            "items" => $items,
            'artTypes' => $artTypes,
        ]);
    }
    

    public function filter(Request $request)
    {
        // Retrieve filter criteria from the request body
        $artType = $request->input('artType');

        // Query items based on the filter criteria
        $items = Item::with('user')->where('artType', $artType)->paginate(3);

        $artTypes = Item::distinct('artType')->pluck('artType');

        // Show the value of the parameter
        return response()->json([
            "items" => $items,
            'artTypes' => $artTypes,
            'filteredType' => $artType,
        ]);
    }

    public function search(Request $request)
    {
        // Retrieve search data from the request body
        $itemName = $request->input('itemName');

        // Query items based on the filter criteria
        $items = Item::with('user')->where('name', 'LIKE', '%' . $itemName . '%')->paginate(3);
        $artTypes = Item::distinct('artType')->pluck('artType');

        // Show the value of the parameter
        return response()->json([
            "items" => $items,
            'artTypes' => $artTypes,
        ]);
    }

    
    public function show(Request $request, $id) {
        $item = Item::find($id);
        if(!$item){
            return 'Item not found.';
        }

        return response()->json([
            "item" => $item,
        ]);
    }
}
