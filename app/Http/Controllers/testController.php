<?php

namespace App\Http\Controllers;

use App\Models\LectureModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
class testController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd("d", "in index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('doctor.lectures.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'Name' => 'required',
            'File' => 'required',
            'D_id' => 'required|integer|min:1|exists:doctors,id',
            'C_id' => 'required|integer|min:1|exists:course,C_id'
        ]);

        $request_data = $request->only(['Name', 'D_id', 'C_id']);
        $lecture = LectureModel::create($request_data);

        $extensionFile = $request->file('File')->getClientOriginalExtension();
        $FileLectureName = $lecture->Lec_id . '.' . $extensionFile;
        $request->file('File')->move(public_path("/images/Lecture/"), $FileLectureName);

        //for update image name in db
        $lecture['File'] = $FileLectureName;
        LectureModel::where('Lec_id', $lecture->Lec_id)
            ->update(['File' => $FileLectureName]);
        session()->flash('success', __('site.added_successfully'));
        return redirect()->to(url('doctor/lectures?c_id=' . $request->C_id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(LectureModel $lecture)
    {
        return $lecture;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(LectureModel $lecture)
    {
        return view('doctor.lectures.edit', compact('lecture'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LectureModel $lecture)
    {
        $request->validate([
            'Name' => 'required',
            'D_id' => 'required|integer|min:1|exists:doctors,id',
            'C_id' => 'required|integer|min:1|exists:course,C_id'
        ]);

        $request_data = $request->only(['Name', 'D_id', 'C_id']);


        if ($request->File) {


            //delete old file
            $path = public_path() . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'Lecture' . DIRECTORY_SEPARATOR . $lecture->File;

            if (file_exists('public/images/Lecture/' . $lecture->File))
                unlink($path);


            $extensionFile = $request->file('File')->getClientOriginalExtension();
            $FileLectureName = $lecture->Lec_id . '.' . $extensionFile;
            $request->file('File')->move(public_path("/images/Lecture/"), $FileLectureName);

            //for update image name in db
            $lecture['File'] = $FileLectureName;
            LectureModel::where('Lec_id', $lecture->Lec_id)
                ->update(['File' => $FileLectureName]);

        }//end of if

        $lecture->update($request_data);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->to(url('doctor/lectures?c_id=' . $request->C_id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(LectureModel $lecture)
    {
        
        $C_id = $lecture->C_id;
        try {

            $lecture->delete();


            $path = public_path() . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'Lecture' . DIRECTORY_SEPARATOR . $lecture->File;
            if (file_exists('public/images/Lecture/' . $lecture->File))
                unlink($path);


        } catch (\Illuminate\Database\QueryException $e) {

            if ($e->getCode() == "23000") { //23000 is sql code for integrity constraint violation
                // return error to user here
                session()->flash('success', __('site.deleted_error'));
                return redirect()->to(url('doctor/lectures?c_id=' . $C_id));
            }
        }

        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->to(url('doctor/lectures?c_id=' . $C_id));
    }
}
