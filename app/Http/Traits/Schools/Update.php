<?php

namespace App\Http\Traits\Schools;

use App\Models\Scholar;
use App\Models\ScholarEducation;
use App\Models\SchoolCourseProspectus;
use App\Http\Resources\Scholar\SearchResource;

trait Update { 
    
    public static function updateProspectus($request){
        $data = SchoolCourseProspectus::where('id',$request->id)->first();
        $data->update($request->except('editable'));
        
        return back()->with([
            'data' => $data,
            'message' => 'Prospectus successfully updated. Thanks',
            'type' => 'bxs-check-circle',
            'color' => 'success'
        ]);
    }

    public static function newProspectus($request){
        $data = ScholarEducation::with('school.school','course','level')->where('id',$request->id)->first();
        $level = $data->level->name;
        $scholar_id = $data->scholar_id;

        $pros = SchoolCourseProspectus::where('school_course_id',$request->subcourse_id)->where('is_active',1)->first();
        $new = [
            'id' => $pros->id,
            'year' => $pros->school_year
        ];
        $prospectus = json_decode($data->information,true);
        $prospectus['id'] = $pros->id;
        $prospectus['year'] = $pros->school_year;
        array_unshift($prospectus['lists'], $new);
        $oldSemesters = $prospectus['prospectus'];

        $newSemesters = json_decode($pros->subjects,true);
       
        if($level == '1st'){
            $oldSemesters[1] = array_replace($oldSemesters[1],$newSemesters[1]);
            $oldSemesters[2] = array_replace($oldSemesters[2],$newSemesters[2]);
            $oldSemesters[3] = array_replace($oldSemesters[3],$newSemesters[3]);
        }else if($level == '2nd'){
            $oldSemesters[2] = array_replace($oldSemesters[2],$newSemesters[2]);
            $oldSemesters[3] = array_replace($oldSemesters[3],$newSemesters[3]);
        }else if($level == '3rd'){
            $oldSemesters[3] = array_replace($oldSemesters[3],$newSemesters[3]);
        }else{

        }
        $prospectus['prospectus'] = $oldSemesters;
        $data->information = json_encode($prospectus);

        if($data->save()){
            $scholar = Scholar::with('profile')
            ->with('program:id,name','subprogram:id,name','category:id,name','status:id,name,type,color,others')
            ->with('education.school.school','education.school.semesters','education.school.gradings','education.course','education.level')
            ->with('enrollments.semester.semester','enrollments.level')
            ->where('id',$scholar_id)
            ->first();
        }
        
        return back()->with([
            'data' => new SearchResource($scholar),
            'message' => 'Prospectus updated successfully. Thanks',
            'type' => 'bxs-check-circle',
            'color' => 'success'
        ]);
    }

    public static function lock($request){
        $data = SchoolCourseProspectus::where('id',$request->id)->update(['is_locked' => $request->is_locked]);
        $data = SchoolCourseProspectus::where('id',$request->id)->first();
        return back()->with([
            'data' => $data,
            'message' => 'Prospectus locked. Thanks',
            'type' => 'bxs-check-circle',
            'color' => 'success'
        ]);
    }

    public static function status($request){
        $data = SchoolCourseProspectus::where('id',$request->id)->update(['is_active' => $request->is_active]);
        $data = SchoolCourseProspectus::where('id',$request->id)->first();

        $update = SchoolCourseProspectus::where('id','!=',$request->id)->where('school_course_id',$data->school_course_id)->update(['is_active' => 0]);

        return back()->with([
            'data' => $data,
            'message' => 'Prospectus status updated. Thanks',
            'type' => 'bxs-check-circle',
            'color' => 'success'
        ]);
    }
}