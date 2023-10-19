<?php

namespace App\Http\Traits\Monitoring;

use App\Models\Release;
use App\Models\Scholar;
use App\Models\ScholarEnrollment;
use App\Models\ScholarEnrollmentBenefit;
use App\Models\ScholarBenefit;

trait Save { 
    
    public function checked($request){
        $releases = Release::where('status_id',13)->where('is_checked',0)->pluck('id');
        $scholars = ScholarEnrollment::whereHas('benefits',function ($query) use ($releases){
            $query->whereIn('release_id',$releases);
        })->groupBy('scholar_id')->pluck('scholar_id');
        
        foreach($scholars as $scholar){
            $enrollees = ScholarEnrollment::where('is_enrolled',1)->where('scholar_id',$scholar)->where('is_benefits_released',0)->get();

            foreach($enrollees as $enrollee){
                foreach($releases as $release){
                    $semester_id = $enrollee->semester_id;
                    $benefits = ScholarEnrollmentBenefit::whereHas('enrollment',function ($query) use ($scholar,$semester_id){
                        $query->where('scholar_id',$scholar)->where('semester_id',$semester_id);
                    })->where('status_id','!=',13)->count();
                    if($benefits == 0){
                        if($enrollee->is_grades_completed){
                            $en = ScholarEnrollment::where('id',$enrollee->id)->update(['is_benefits_released' => 1,'is_completed' => 1]);
                        }else{
                            $en = ScholarEnrollment::where('id',$enrollee->id)->update(['is_benefits_released' => 1]);
                        }
                    }
                    Release::where('id',$release)->update(['is_checked' => 1]);
                }
            }
        }
    }

    public function terminate($request){
        switch($request->option){
            case 2:
                Scholar::where('id',$request->scholar_id)->update(['status_id' => 8]);
            break;
            case 3:

            break;
        }
        $id = $request->enrollment_id;
        ScholarEnrollment::where('id',$id)->update(['is_checked' => 1]);
    }
    
}