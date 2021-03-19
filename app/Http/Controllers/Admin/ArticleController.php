<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\Article;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    public function index()
    {
        $data = Article::where('type',$type)->orderBy('id','DESC')->paginate(3);
        return ArticleResource::collection($data);
    }

    public function paginated($type)
    {
        $data = Article::where('type',$type)->orderBy('id','DESC')->paginate(5);
        return ArticleResource::collection($data);
    }

    public function store(Request $request)
    {
        $article = $request->isMethod('put') ? Article::findOrFail($request->input('id')) : new Article;
        $article->title = ucwords(strtolower($request->input('title')));
        $article->content = $request->input('content');
        $article->type = $request->input('type');
        $article->user_id = Auth::user()->id;
        if($article->save()){

            if($request->input('photos') != null){
                $datas = $request->input('photos');
    
                foreach($datas as $data){
                    $image = $data['path'];  // your base64 encoded
                    
                    $img = explode(',', $image);
                    $ini =substr($img[0], 11);
                    $type = explode(';', $ini);
                    
                    if($type[0] == 'png'){
                        $image = str_replace('data:image/png;base64,', '', $image);
                    }else{
                        $image = str_replace('data:image/jpeg;base64,', '', $image);
                    }
    
                    $image = str_replace(' ', '+', $image);
                    $imageName =date('YmdHis').'-'.\Str::random(5).'.'.'jpeg';
                    \File::put(public_path('images/articles'). '/' . $imageName, base64_decode($image));
                    
                    $article->image = $imageName;
                    $article->save();
                }
            }
        }

        return new ArticleResource($article);
    }
}
