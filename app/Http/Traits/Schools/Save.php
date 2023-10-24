<?php

namespace App\Http\Traits\Schools;

use App\Models\ListDropdown;
use App\Models\Scholar;
use App\Models\ScholarEnrollment;
use App\Models\SchoolGrading;
use App\Models\SchoolSemester;
use App\Models\SchoolCourseProspectus;
use App\Http\Resources\DefaultResource;

trait Save { 
    
    public static function semester($request){
        $data = SchoolSemester::create($request->all());
        $update = SchoolSemester::where('id','!=',$data->id)->where('school_id',$data->school_id)->update(['is_active' => false]);
        $data = SchoolSemester::with('semester')->where('id',$data->id)->first();
        if($data){
            $semester = $data->semester->name;
            $school_id = $data->school_id;
            $semester_id = $data->id;
            $scholars = Scholar::select('id')->whereHas('status',function ($query){
                $query->where('type','ongoing');
            })->withWhereHas('education',function ($query) use ($school_id){
                $query->select('id','scholar_id','level_id')->where('school_id',$school_id)
                ->withWhereHas('level',function ($query){
                    $query->select('id','name','others');
                });
            })->get();
        
            $enrollmentsData = $scholars->map(function ($scholar) use ($semester_id,$semester){
                $fill = [
                    'scholar_id' => $scholar->id,
                    'semester_id' => $semester_id,
                    'level_id' => $scholar->education->level_id ,
                    'attachment' => json_encode([
                        'grades' => [],
                        'enrollments' => []
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                if($semester == 'Summer'){
                    if($scholar->education->level->others == 'Third Year'){
                        return $fill;
                    }else{
                        return [];
                    }
                }else{
                    return $fill;
                }
               
                // $information = json_decode($scholar->education->information);
                // if(isset($information->checker)){
                //     foreach ($information->checker as $record) {
                //         if ($record["year"] === $scholar->education->level->others && $record["semester"] === $semester) {
                //             $is_empty = $record["is_empty"];
                //             break; 
                //         }
                //     }
                // }else{
                  
                // }
            });
            $enrollments = ScholarEnrollment::insert($enrollmentsData->all());
        }

        return back()->with([
            'data' => new DefaultResource($data),
            'message' => 'Semester successfully created. Thanks',
            'type' => 'bxs-check-circle',
            'color' => 'success'
        ]);
    }

    public static function prospectus($request){
        $level = ListDropdown::where('classification','Level')->select('name','others')->get();
        $years = $request->years;
        $term = $request->term;
        $semesters = ListDropdown::where('classification',$term)->pluck('name');

        $group = []; $courses = [];

        for ($i = 0; $i < $years; ++$i) {
            $sem = [];
            foreach($semesters as $key=>$semester){
                $sem[] = ['semester' => $semester,'total' => 0,'courses' => $courses];
            }
            $group[] = [
                'year_level' => $level[$i]['others'],
                'year_ordinal' => $level[$i]['name'],
                'semesters' => $sem
            ];
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