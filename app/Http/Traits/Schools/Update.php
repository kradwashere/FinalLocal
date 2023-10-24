<?php

namespace App\Http\Traits\Schools;

use App\Models\SchoolCourseProspectus;
use App\Models\ScholarEducation;

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
        $data = ScholarEducation::where('id',$request->id)->first();
        $pros = SchoolCourseProspectus::where('school_course_id',$request->subcourse_id)->where('is_active',1)->first();
        $new = [
            'id' => $pros->id,
            'year' => $pros->school_year
        ];
        $prospectus = json_decode($data->information,true);
        $prospectus['id'] = $pros->id;
        $prospectus['year'] = $pros->school_year;
        array_unshift($prospectus['lists'], $new);
        // dd($prospectus);
        
        // $information = [
        //     'id' => $pros->id,
        //     'year' => $pros->school_year,
        //     'lists' => $lists,
        //     'prospectus' => json_decode($pros->subjects)

            
        // ];
        $data->information = json_encode($prospectus);
        $data->save();
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