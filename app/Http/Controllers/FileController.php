<?php

namespace App\Http\Controllers;

use App\Comment;
use App\File;
use App\Helper;
use getID3;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $direction = 'asc';

        if (isset($_GET['direction']) && $_GET['direction'] == 'desc') {
            $direction = 'desc';
        }

        if (isset($_GET['search'])) {

            if (isset($_GET['orderBy'])) {
                $files = File::where('file_name', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy($_GET['orderBy'], $direction)
                    ->paginate(15);
            } else {
                $files = File::where('file_name', 'like', '%' . $_GET['search'] . '%')
                    ->paginate(15);
            }

        } else {
            if (isset($_GET['orderBy'])) {
                $files = File::orderBy($_GET['orderBy'], $direction)
                    ->paginate(15);
            } else {
                $files = DB::table('files')
                    ->paginate(15);
            }

        }

        return view('index', ['files' => $files]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //dd($_FILES, $_POST, Input::all());
        $request->validate([
            'file' => ['file', 'required'],
        ]);

        $validator = Validator::make($request->all(), [
            'file' => 'file',
        ]);

        $validator->after(function ($validator) {
            if (0 && $_FILES['file']['size'] <= 0) {
                //dd($_FILES,Input::file('file'));
                $validator->errors()->add('field', 'Please upload the file!');
            }
        });

        if ($validator->fails()) {
            return redirect(route('create'))
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->file('file')->isValid()) {

            if ( $request->file('file')->getClientOriginalName() == '.htaccess') {
                return redirect('/')->with('success', 'File is not successfully saved');
            }

            $file_path = $request->file('file')->storeAs('public', $request->file('file')->getClientOriginalName());

            $file = File::create([
                'file_name' => $request->file('file')->getClientOriginalName(),
                'file_size' => Helper::formatSizeUnits($request->file('file')->getSize()),
                'download_link' => 'http://' . $request->server("HTTP_HOST") . Storage::url($request->file('file')->getClientOriginalName()),
                ]);

            if (
                Helper::endsWithArr($request->file('file')->getClientOriginalName(), [
                    '.jpeg', '.jpg', 'gif', 'png'
                ])) {
                $img = Image::make($_FILES['file']['tmp_name'])->fit(50, 50);

                $img->save('' . $file->id . '.jpg');
            }


            return redirect('/')->with('success', 'File is successfully saved');
        } else {
            return redirect('/')->with('success', 'File is not successfully saved');
        }

        //dd($request);

        //$student = File::create($validatedData);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $file = File::findOrFail($id);

        $comments = Comment::all();

        $getID3 = new getID3();

        $thisFileInfo = $getID3->analyze(realpath('../storage/app/public/') .'/' . $file->file_name);

        return view('info', ['file' => $file, 'info' => $thisFileInfo, 'comments' => $comments]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $file = File::findOrFail($id);

        return view('edit', compact('file'));
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
        return redirect('/')->with('success', 'Student is successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //dd(phpinfo());
        $file = File::findOrFail($id);
        $file->delete();

        Comment::where('file_id', '=', $id)->delete();

        Storage::delete('public/' . $file->file_name);

        Storage::disk('icons')->delete(($file->id) . '.jpg');

        return redirect('/')->with('success', 'Student is successfully deleted');
    }

}
