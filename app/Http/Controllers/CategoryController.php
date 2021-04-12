<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use File;

class CategoryController extends Controller
{
    //
	public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'type'=>'required',
            'name'=>'required',
			'fair_id'=>'required',
            'resources'=>''
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();
        $category = new Category();
        $category->type = $data('type');
		$category->name = $data('name');
        $category->fair_id = $data('fair_id');
		$category->resources = $data('resources');
        $category->save();
		
		return [
            'success' => 201,
            'data' => $category
        ];

    }
	
	public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'type'=>'required',
            'name'=>'required',
			'fair_id'=>'required',
            'resources'=>''
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();
        $category = Category::find($data['id']);
        $category->type = $data('type');
		$category->name = $data('name');
        $category->fair_id = $data('fair_id');
		$category->resources = $data('resources');
        $category->save();
		
		return [
            'success' => 201,
            'data' => $category
        ];
    }
	
	public function delete(Request $request){

        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'type'=>'required',
			'fair_id'=>'required'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();
		
        $category = Category::find($data['id'])
		  ->where('fair_id',$data['fair_id'])
		  ->where('type',$data['type']);
		  
        $category->delete();
		
		return [
            'success' => 201,
            'message' => 'Categoría borrada exitósamente'
        ];
    }

    public function to_list($type){

        return [
            'success' => 201,
            'data' => Category::where('type',$type)->get()
        ];
    }


}
