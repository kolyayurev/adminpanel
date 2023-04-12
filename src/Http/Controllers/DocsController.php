<?php

namespace KY\AdminPanel\Http\Controllers;

use Illuminate\Mail\Markdown;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class DocsController extends Controller
{

    public function index()
    {
        $menu = Markdown::parse(file_get_contents(docs_path().'summary.md'));

        return view('adminpanel::docs.index',compact('menu'));
    }

    public function content(Request $request)
    {
        $path = $request->path;
        $filename = Str::afterLast($path,'/');
        $folder = $request->folder;

        $folder = Str::startsWith($path,$folder)?'':$folder;

        $path = realpath(docs_path().implode('/',array_filter([$folder,$path])));

        $currentFolder = Str::betweenFirst($path,docs_path(),$filename);

        $content = Markdown::parse(file_get_contents($path));

        return response()->json([
            'status' => 'success',
            'content' => view('adminpanel::docs.content',compact('content','currentFolder'))->render()
        ]);
    }
}
