<?php

namespace App\Http\Controllers\Dashboard\DoctorDashboard;

use App\Models\LectureModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class LectureControllerDoctor extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        dd("d", "in index");

//        $request = request();
//        //dd("d", $request->c_id);
//        $c_id = $request->c_id;
//        $lectures = LectureModel::where([
//            ['C_id', '=', $request->c_id],
//            ['D_id', '=', Auth::user()->id],
//        ])->where(function ($q) use ($request) {
//
//            return $q->when($request->search, function ($query) use ($request) {
//
//                return $query->where('Name', 'like', '%' . $request->search . '%');
//
//            });
//
//        })->orderBy('Lec_id', 'DESC')->paginate(12);
//
//
//        return view('doctor.lectures.index', compact('lectures', 'c_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $c_id = $request->c_id;
        return view('doctor.lectures.create', compact( 'c_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
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
     * @param \App\Models\LectureModel $lectureModel
     * @return \Illuminate\Http\Response
     */
    public function show(LectureModel $lectureModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\LectureModel $lectureModel
     * @return \Illuminate\Http\Response
     */
    public function edit(LectureModel $lecture)
    {
        return view('doctor.lectures.edit', compact('lecture'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\LectureModel $lectureModel
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
     * @param \App\Models\LectureModel $lectureModel
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
