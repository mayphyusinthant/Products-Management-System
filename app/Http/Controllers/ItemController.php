<?php

namespace App\Http\Controllers;
use App\Models\Item;
use Illuminate\Validation\ValidationException;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;

class ItemController extends Controller
{
    // Explore () function shows items / products owned by other users. 
    public function explore()
    {
        $artTypes = Item::distinct('artType')->pluck('artType');
         
        // Retrieve the current user
        $currentUser = Auth::user();
        // Retrieve items not owned by the current user
        $items = Item::whereNotIn('owner', [$currentUser->id])->get()->load('user');
        return Inertia::render('Item/Explore', [
            "items" => $items,
            'artTypes' => $artTypes,

        ]);
    }

    public function index()
    {
        $artTypes = Item::distinct('artType')->pluck('artType');
         
        // Retrieve the current user
        $currentUser = Auth::user();
        // Retrieve items  owned by the current user
        $items = Item::where('owner', $currentUser->id)->get()->load('user');

        return Inertia::render('Item/Index', [
            "items" => $items,
            'artTypes' => $artTypes,

        ]);
    }

    public function view(Request $request, $id){

        $item = Item::find($id);
        if(!$item){
            return redirect("/items")->with('error', 'Item not found.');
        }

        $referrer = $request->headers->get('referer');
       
        return Inertia::render('Item/View', [
            'item' => $item,
            'referrer' => $referrer,
        ]);
    }


    public function filter(Request $request)
    {
        $artTypes = Item::distinct('artType')->pluck('artType');
        $artType = $request->input('artType');
        
        $referrer = $request->headers->get('referer');

        if (strpos($referrer, '/admin') !== false) { 
            $items = Item::with('user')->where('artType', $artType)->paginate(5);
            $items->withQueryString();

            return Inertia::render('Admin/Index', [
                'items' => $items,
                'artTypes' => $artTypes,
                'filteredType' => $artType,
            ]);
        }
        else if (strpos($referrer, 'items/explore') !== false) { 
             // Retrieve the current user
            $currentUser = Auth::user();
            // Retrieve items not owned by the current user - and filter
            $items = Item::whereNotIn('owner', [$currentUser->id])
                    ->where('artType', $artType)
                    ->get()
                    ->load('user');
            return Inertia::render('Item/Explore', [
                'items' => $items,
                'artTypes' => $artTypes,
                'filteredType' => $artType,
            ]);
        }
        else{
            $currentUser = Auth::user();
            // Retrieve items  owned by the current user
            $items = Item::where('owner', $currentUser->id)->get()->load('user');
            return Inertia::render('Item/Index', [
                'items' => $items,
                'artTypes' => $artTypes,
                'filteredType' => $artType,
            ]);
        }
    }


    public function create()
    {
        return Inertia::render('Item/Create', [
            'edit' => false,
        ]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
        'name' => ['required', 'string', 'max:255'],
        'description' => ['required', 'string', 'max:255'],
        'artType' => ['required', 'string', 'max:255'],
        'medium' => ['required', 'string', 'max:255'],
        'size' => ['required'],
        'price' => ['required', 'integer'],
        'status' => ['required', 'string', 'max:255'],
        'img' => ['required'],
        ]);

        if ($validator->fails()) {
            
            // If validation fails, return with errors
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $userId = Auth::id();
        // Validation passed, create the item
        $validatedData = $validator->validated();
        $validatedData['img'] = 'images/' .basename($request->input('img'));
        $validatedData['size'] = $request->input('size');
        $validatedData['owner'] = $userId;
        Item::create($validatedData);

        $referrer = $request->headers->get('referer');
        if (strpos($referrer, '/admin') !== false) {
            return  Redirect::route('admin.index')->with('success', 'New Item has been created successfully.');       
        }else{
            return  Redirect::route('items.index')->with('success', 'New Item has been created successfully.');       
        }
    }

    public function edit(Request $request, $id){
        $item = Item::find($id);
        if(!$item){
            return redirect("/items")->with('error', 'Item not found.');
        }

        // Retrieve the current user
        $currentUser = Auth::user();
        // Authorization Check
        if ($item->owner !== $currentUser->id) {
            return "You are not allowed to edit. Only owner can edit.";
        }

        return Inertia::render('Item/Create', [
            'edit' => true,
            'item' => $item,
        ]);
    }



    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
        'name' => ['required', 'string', 'max:255'],
        'description' => ['required', 'string', 'max:255'],
        'artType' => ['required', 'string', 'max:255'],
        'size' => ['required', 'string', 'max:255'],
        'medium' => ['required', 'string', 'max:255'],
        'price' => ['required', 'integer'],
        'status' => ['required', 'string', 'max:255'],
        'img' => ['required']
    ]);

    if ($validator->fails()) {
        // If validation fails, return with errors
        throw ValidationException::withMessages($validator->errors()->toArray());
    }

    // Retrieve the current user
    $currentUser = Auth::user();
    

    if($validator->validated()){
        $item = Item::find($id);
        $item->name = $request->input('name');
        $item->description = $request->input('description');
        $item->artType = $request->input('artType');
        $item->size = $request->input('size');
        $item->medium = $request->input('medium');
        $item->price = $request->input('price');
        $item->status = $request->input('status');
        $item->img = 'images/' .basename($request->input('img'));

        // Authorization Check
        if ($item->owner !== $currentUser->id) {
            return "You are not allowed to update. Only owner can update.";;
        }else{
            $item->save();
            // Redirect or render as needed
            $referrer = $request->headers->get('referer');
            if (strpos($referrer, '/admin') !== false) {
                return  Redirect::route('admin.index')->with('success', 'Item has been updated successfully.');
            }
            else{
                return  Redirect::route('items.index')->with('success', 'Item has been updated successfully.');
            }
        }
        
    }
    
    }

    public function delete(Request $request, $id){
        $item = Item::find($id);
        if(!$item){
            return redirect("/items")->with('error', 'Item not found.');
        }
        else{
            // Retrieve the current user
            $currentUser = Auth::user();
            // Authorization Check
            if ($item->owner !== $currentUser->id) {
                return "You are not allowed to delete. Only owner can delete.";
            }
            else{
                $item->delete();
                $referrer = $request->headers->get('referer');
                if (strpos($referrer, '/admin') !== false) {
                    return  redirect("/admin/items")->with('success', 'Item has been deleted successfully.');
                }else{
                    return  redirect("/items")->with('success', 'Item has been deleted successfully.');
                }
            }
        }
    }

    // Admin Side 
    public function admin_item()
    {
        $artTypes = Item::distinct('artType')->pluck('artType');
         
        $items = Item::with('user')->paginate(5);
        $items->withQueryString();
       // return $items;

        return Inertia::render('Admin/Index', [
            "items" => $items,
            'artTypes' => $artTypes,
        ]);
    }

}
