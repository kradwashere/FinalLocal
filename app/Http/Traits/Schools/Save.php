<?php

namespace App\Http\Traits\Schools;

use App\Models\Scholar;
use App\Models\ScholarEnrollment;
use App\Models\SchoolGrading;
use App\Models\SchoolSemester;
use App\Models\SchoolCourseProspectus;
use App\Http\Resources\DefaultResource;

trait Save { 
    
    public static function semester($request){
        $data = new DefaultResource(SchoolSemester::create($request->all()));
        $update = SchoolSemester::where('id','!=',$data->id)->where('school_id',$data->school_id)->update(['is_active' => false]);
        if($data){
            $school_id = $data->school_id;
            $semester_id = $data->id;
            $scholars = Scholar::select('id')->whereHas('status',function ($query){
                $query->where('type','ongoing');
            })->withWhereHas('education',function ($query) use ($school_id){
                $query->select('id','scholar_id','level_id')->where('school_id',$school_id);
            })->get();
        
            $enrollmentsData = $scholars->map(function ($scholar) use ($semester_id){
                return [
                    'scholar_id' => $scholar->id,
                    'semester_id' => $semester_id,
                    'level_id' => ($scholar->education->level_id) ? $scholar->education->level_id : 24 ,
                    'attachment' => json_encode([
                        'grades' => [],
                        'enrollments' => []
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            });
            // dd($enrollmentsData);
            $enrollments = ScholarEnrollment::insert($enrollmentsData->all());
        }

        return back()->with([
            'data' => $data,
            'message' => 'Semester successfully created. Thanks',
            'type' => 'bxs-check-circle',
            'color' => 'success'
        ]);
    }

    public static function prospectus($request){
        $level = ['First Year','Second Year','Third Year','Fourth Year','Fifth Year'];
        $semester = ['First Semester', 'Second Semester', 'Summer Class'];
        $trimester = ['First Term', 'Second Term', 'Third Term', 'Midyear'];
        $quarter = ['First Term', 'Second Term', 'Third Term','Fourth Term'];

        $years = $request->years;
        $type = $request->subtype;

        if($type == 'Semester'){
            $semesters = $semester;
        }else  if($type == 'Trimester'){
            $semesters = $trimester;
        }else{
            $semesters = $quarter;
        }
        $group = []; $courses = [];

        for ($i = 0; $i < $years; ++$i) {
            $sem = [];
            foreach($semesters as $key=>$semester){
                $sem[] = ['semester' => $semester,'total' => 0,'courses' => $courses];
            }
            $group[] = ['year_level' => $level[$i],'semesters' => $sem];
        }
        $request['subjects'] = json_encode($group,true);
        $request['added_by'] = \Auth::user()->id;

        $data = SchoolCourseProspectus::create($request->all());
        $data = SchoolCourseProspectus::where('id',$data->id)->first();

        return back()->with([
            'data' => $data,
            'message' => 'Prospectus successfully added. Thanks',
            'type' => 'bxs-check-circle',
            'color' => 'success'
        ]);
    }

    public static function grading($request){
        $data = SchoolGrading::create($request->all());
        return back()->with([
            'data' => $data,
            'message' => 'Grade successfully added. Thanks',
            'type' => 'bxs-check-circle',
            'color' => 'success'
        ]);
    }

}