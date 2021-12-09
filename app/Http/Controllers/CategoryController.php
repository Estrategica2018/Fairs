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
            'resource'=>''
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();
        $category = new Category();
        $category->type = $data['type'];
        $category->name = $data['name'];
        $category->fair_id = $data['fair_id'];
        $category->resources = json_encode($data['resource']);
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
            'resource'=>''
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();
        $category = Category::find($data['id']);
        if($category){
            $category->type = $data['type'];
            $category->name = $data['name'];
            $category->fair_id = $data['fair_id'];
            $category->resources = json_encode($data['resource']);
            $category->save();

            return [
                'success' => 201,
                'data' => $category
            ];
        }
        return [
            'success' => 401,
            'data' => 'No se encontro la categoría'
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

    public function to_list(Request $request, $fair_id,$type){

        if( $type =='all' ){
            return [
            'success' => 201,
            'data' => Category::where([['fair_id',$fair_id]])->get(),
            'data_category' => Category::where([['fair_id',$fair_id],['category_id',null]])->get(),
            'data_subcategory' =>
                Category::with('category')->whereHas('category')->where([['fair_id',$fair_id]])->get(),
            ];
        }
        return [
            'success' => 201,
            'data' => Category::where([['type',$type],['fair_id',$fair_id]])->get()
        ];
    }

    public function get (Request $request , $category_id){

        $category = Category::find($category_id);

        if($category){
            return [
                'success' => true,
                'data' => $category,
            ];
        }

        return [
            'success' => 404,
            'message' => 'Categoría no encontrada',
        ];

    }

    public function create_sub_category(Request $request){

        $validator = Validator::make($request->all(), [
            'category_id'=>'required',
            'type'=>'required',
            'name'=>'required',
            'fair_id'=>'required',
            'resource'=>''
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();
        $category = new Category();
        $category->type = $data['type'];
        $category->name = $data['name'];
        $category->fair_id = $data['fair_id'];
        $category->category_id = $data['category_id'];
        $category->resources = json_encode($data['resource']);
        $category->save();

        return [
            'success' => 201,
            'data' => $category
        ];

    }

    public function update_sub_category(Request $request){
        dd($request->all());
    }

    public function get_sub_category(Request $request, $sub_category_id){

        $subcategory = Category::with('category')->whereHas('category')->where('id',$sub_category_id)->first();

        if($subcategory){
            return [
                'success' => true,
                'data' => $subcategory,
            ];
        }

        return [
            'success' => 404,
            'message' => 'Categoría no encontrada',
        ];
    }

}
