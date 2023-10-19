<?php

namespace App\Http\Traits\Benefits;

use App\Models\Release;
use App\Models\Scholar;
use App\Models\ScholarEnrollment;
use App\Models\ScholarEnrollmentBenefit;
use App\Http\Resources\NameResource;
use App\Http\Resources\Scholar\Benefits\ListResource;

trait BenefitTrait { 
    
    public function generate(){
        $date = now();
        $pending = ScholarEnrollment::whereHas('benefits',function ($query) use ($date){
            $query->where('status_id',11)->where('month','<=',$date);
        })->groupBy('scholar_id')->pluck('scholar_id');
        $scholars = Scholar::with('profile')->whereIn('id',$pending)->get();
        $month = date('Y').'-'.date('m').'-01';
        $data = [
            'pending' => $pending,
            'scholars' => NameResource::collection($scholars),
            'month' => date('F', mktime(0, 0, 0, date("m"), 10)),
            'count' => Release::whereYear('created_at', '=', date("Y"))->whereMonth('created_at', '=', date("m"))->count(),
            'ongoing' => Scholar::whereHas('status', function ($query) {
                $query->where('type','Ongoing');
            })->count(),
            'received' => ScholarEnrollmentBenefit::where('month',$month)->where('benefit_id',1)->where('status_id',13)->count()
        ];
        return $data;
    }

    public function benefits($info){
        $scholars = (!empty(json_decode($info))) ? json_decode($info) : NULL;
        $month = now();

        $data = Scholar::select('id','account_no','program_id')
        ->with('program:id,name')
        ->with('profile:scholar_id,firstname,lastname,middlename')
        ->withWhereHas('enrollments', function ($query) use ($month) {
            $query->select('id','scholar_id','semester_id')
            ->withWhereHas('semester', function ($query) {
                $query->select('id','academic_year','semester_id')
                ->withWhereHas('semester', function ($query) {
                    $query->select('id','name');
                });
            })
            ->withWhereHas('benefits', function ($query) use ($month) {
                $query->select('id','enrollment_id','amount','month','benefit_id')->where('status_id',11)->where('month','<=',$month)
                ->withWhereHas('benefit', function ($query) use ($month) {
                    $query->select('id','name','type','short','regular_amount','summer_amount');
                });
            });
        })
        ->whereIn('id',$scholars)
        ->get();

        return ListResource::collection($data);
    }
}