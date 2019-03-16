<?php

namespace App\Http\Controllers\API;

use Image;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Category::latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=> 'required|max:191',
            'category_image' => 'required',
        ]);



        $name = time().'.'.explode('/',explode(':',substr($request->category_image, 0, strpos($request->category_image, ';')))[1])[1];

        Image::make($request->category_image)->save(public_path('img/category/').$name);

        $request->merge(['category_image' => $name]);

        return Category::create([
            'name' => $request['name'],
            'slug' => str_slug($request['name']),
            'category_image' => $name,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $currentPhoto = $category->category_image;

        if($request->category_image != $currentPhoto){
            $filename = time().'.'.explode('/',explode(':', substr($request->category_image, 0, strpos($request->category_image, ';')))[1])[1];

            Image::make($request->category_image)->save(public_path('img/category/').$filename);

            $request->merge(['category_image' => $filename]);

            $category_image = \public_path('img/category/').$currentPhoto;
            if (\file_exists($category_image)) {
                @unlink($category_image);
            }

            $category->update([
                'name' => $request['name'],
                'slug' => str_slug($request['name']),
                'category_image' => $filename,
            ]);
        }

        $category->update([
            'name' => $request['name'],
            'slug' => str_slug($request['name']),
        ]);
        return ['message' => 'success'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->category_image;
        $category_image = \public_path('img/category/').$category->category_image;
            if (file_exists($category_image)) {
                @unlink($category_image);
            }
        $category->delete();
    }
}
