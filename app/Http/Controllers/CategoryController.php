<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Medicine;

class CategoryController extends Controller
{
    public function index(){

        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request){

        $request->validate([
            'name'=>'required|min:3|max:100',
            'description'=>'required|min:5'
        ]);

        $category = new Category();

        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        return redirect('/categories');
    }

    public function edit($id){

        $category = Category::find($id);

        return view('categories.edit', compact('category'));

    }

    public function update(Request $request,$id){

        $category = Category::find($id);

        $category->name = $request->name;
        $category->description = $request->description;

        $category->save();
        
        return redirect('/categories');
    
    }

    public function destroy($id){

    $category = Category::find($id);

    //Check if Medicine exist under this category

    $medicineCount = Medicine::where('category_id', $id)->count();

    if($medicineCount > 0){

        return redirect ('/categories')
            ->with('error', 'Cannot delete category because medicine are assigned to it.');
    }

    $category->delete();

    return redirect('/categories')
        ->with('success', 'Category deleted successfully');

    }
}
