<?php

namespace App\Http\Traits\Monitoring;

use App\Models\Scholar;
use App\Models\Release;
use App\Models\ListAgency;
use App\Models\ListStatus;
use App\Models\SchoolCampus;
use App\Models\SchoolSemester;
use App\Models\ScholarEnrollment;
use App\Models\ScholarEnrollmentInfo;
use App\Models\ScholarEnrollmentBenefit;
use App\Http\Resources\MonitoringResource;
use App\Http\Resources\Monitoring\TerminationResource;
use App\Http\Resources\Monitoring\MissedResource;
use App\Http\Resources\Monitoring\UnenrolledResource;
use App\Http\Resources\Monitoring\BenefitsResource;
use App\Http\Resources\Monitoring\GradesResource;

trait Count { 
    
    public static function grades($request){
        $semester = $request->semester_id;
        $scholars = Scholar::select('id','spas_id','awarded_year','status_id')
        ->with('profile:id,scholar_id,firstname,lastname,middlename')
        ->withwhereHas('enrollments', function ($query) use ($semester){
            $query->select('id','scholar_id','level_id','semester_id')
            ->with('level','semester:id,semester_id,academic_year','semester.semester:id,name')
            ->withwhereHas('lists',function ($query){
                $query->where('grade',NULL);
            })->whereHas('semester',function ($query){
                $query->where('is_active',0);
            })->when($semester, function ($query, $semester) {
                $query->where('semester_id',$semester);
            });
        })
        ->withWhereHas('education', function ($query){
            $query->select('id','scholar_id','school_id')
            ->withWhereHas('school', function ($query){
                $query->select('id','school_id','campus')
                ->withWhereHas('school', function ($query){
                    $query->select('id','name','shortcut');
                })
                ->withWhereHas('gradings', function ($query){
                    $query->select('id','grade','school_id');
                });
            });
        })
        ->withWhereHas('status',function ($query){
            $query->select('id','name','color')->where('type','ongoing');
        })
        ->paginate(5);
        return GradesResource::collection($scholars);
    }

    public static function benefits($request){
        $date = date('Y-m-d');
        $scholars = Scholar::select('id','spas_id','awarded_year','status_id')
        ->with('profile:id,scholar_id,firstname,lastname,middlename')
        ->withwhereHas('enrollments', function ($query) use ($date){
            $query->select('id','scholar_id','level_id','semester_id')
            ->with('level','semester:id,semester_id,academic_year','semester.semester:id,name')
            ->withWhereHas('benefits',function ($query) use ($date){
                $query->whereIn('status_id',[11,12])->where('month','<=',$date);
            });
        })
        ->when($request->semester_id, function ($query, $semester) {
            $query ->whereHas('enrollments',function ($query, $semester){
                $query->where('semester_id',$semester);
            });
        })
        ->withWhereHas('status',function ($query){
            $query->select('id','name','color')->where('type','ongoing');
        })
        ->paginate(5);
        return BenefitsResource::collection($scholars);
    }

    public static function termination($request){
        $data = ScholarEnrollment::select('id','scholar_id','semester_id','level_id')
        ->with('level:id,name,others')
        ->with('semester:id,academic_year,year,semester_id','semester.semester:id,name,others')
        ->with('scholar:id,spas_id','scholar.profile:scholar_id,firstname,middlename,lastname')
        ->with('scholar.status:id,name,color,others')
        ->with([
            'lists' => function ($query) {
                $query->select('enrollment_id','code','subject','unit')
                ->where('is_failed', 1); // Include only 'lists' where is_failed is 1
            }
        ])
        ->withCount([
        'lists' => function ($query) {
            $query->where('is_failed',1)->groupBy('enrollment_id');
        }])
        ->when($request->semester_id, function ($query, $semester) {
            $query->where('semester_id',$semester);
        })
        ->whereHas('scholar',function ($query){
            $query ->whereHas('status',function ($query){
                $query->where('type','ongoing');
            });
        })
        ->where('is_checked',0)
        ->having('lists_count','>', 1)
        ->get();

        // return $data;
        return TerminationResource::collection($data);

        // $scholars = Scholar::with('profile:id,scholar_id,firstname,lastname,middlename')->whereIn('id',$data)->get();
        // return MonitoringResource::collection($scholars);
    }


    public function enrolled($request){
        $data = ScholarEnrollment::where('is_enrolled',1)
        ->whereHas('semester',function ($query){
            $query->where('is_active',1);
        })
        ->when($request->semester_id, function ($query, $semester) {
            $query->whereHas('semester',function ($query) use ($semester){
                $query->where('id',$semester);
            });
        })
        ->count();
        return $data;
    }

    public static function missed($request){
        $keyword = $request->keyword;
        $scholars = MissedResource::collection(
            Scholar::with('profile:id,scholar_id,firstname,lastname,middlename')
            ->withWhereHas('status',function ($query){
                $query->where('type','ongoing');
            })
            ->whereHas('profile',function ($query) use ($keyword) {
                $query->when($keyword, function ($query, $keyword) {
                    $query->where(\DB::raw('concat(firstname," ",lastname)'), 'LIKE', '%'.$keyword.'%')
                    ->where(\DB::raw('concat(lastname," ",firstname)'), 'LIKE', '%'.$keyword.'%');
                });
            })
            ->withWhereHas('enrollments', function ($query) {
                $query->withWhereHas('semester',function ($query){
                    $query->select('id','academic_year','year','semester_id')
                    ->with('semester:id,name,others')
                    ->where('is_active',0);
                })->withWhereHas('level',function ($query){
                    $query->select('id','name','others');
                })
                ->where('is_enrolled',0);
            })->paginate(5)->withQueryString()
        );
        return $scholars;
    }

    public static function unenrolled($request){
        $keyword = $request->keyword;
        $scholars = UnenrolledResource::collection(
            Scholar::with('profile:id,scholar_id,firstname,lastname,middlename')
            ->withWhereHas('status',function ($query){
                $query->where('type','ongoing');
            }) 
            ->whereHas('profile',function ($query) use ($keyword) {
                $query->when($keyword, function ($query, $keyword) {
                    $query->where(\DB::raw('concat(firstname," ",lastname)'), 'LIKE', '%'.$keyword.'%')
                    ->where(\DB::raw('concat(lastname," ",firstname)'), 'LIKE', '%'.$keyword.'%');
                });
            })
            ->withWhereHas('enrollments', function ($query) {
                $query->withWhereHas('semester',function ($query){
                    $query->select('id','academic_year','year','semester_id')
                    ->with('semester:id,name,others')
                    ->where('is_active',1);
                })->withWhereHas('level',function ($query){
                    $query->select('id','name','others');
                })
                ->where('is_enrolled',0);
            })->whereHas('education',function ($query){
                $query->whereHas('school',function ($query){
                    $query->whereHas('semesters',function ($query){
                        $query->where('is_active',1);
                    });
                });
            })
            ->paginate(5)->withQueryString()
        );
        return $scholars;


        $data = Scholar::whereHas('status',function ($query){
            $query->where('type','ongoing');
        })->whereHas('enrollments', function ($query) {
            $query->whereHas('semester',function ($query){
                $query->where('is_active',1);
            })->where('is_enrolled',0);
        })->whereHas('education',function ($query){
            $query->whereHas('school',function ($query){
                $query->whereHas('semesters',function ($query){
                    $query->where('is_active',1);
                });
            });
        })->pluck('id');

        $scholars = Scholar::with('profile:id,scholar_id,firstname,lastname,middlename')->whereIn('id',$data)->get();
        return MonitoringResource::collection($scholars);
    }

    public static function scholars($request){
        $data = Scholar::whereHas('status',function ($query){
            $query->where('type','Ongoing');
        })
        ->when($request->semester_id, function ($query, $semester) {
            $query->whereHas('enrollees',function ($query) use ($semester){
                $query->whereHas('semester',function ($query) use ($semester){
                    $query->where('id',$semester);
                });
            });
        })
        ->count();
        return $data;
    }

    public function semesters($request){
        // where('year',$request->semester_year)
        $data = SchoolSemester::where('is_active',1)->pluck('school_id');
        return $data;
    }

    public function schools(){
        $agency_id = config('app.agency');
        $region = ListAgency::select('region_code')->where('id',$agency_id)->first();
        $region = $region->region_code;

        $data = SchoolCampus::query()->whereHas('municipality',function ($query) use ($region){
            $query->whereHas('province',function ($query) use ($region){
                $query->whereHas('region',function ($query) use ($region){
                    $query->where('code',$region);
                });
            });
        })->count();
        return $data;
    }

    public function statuses(){
        $statuses = ListStatus::select('id','name','color','type')->where('type','ongoing')->withCount('status')->orderBy('status_count', 'desc')->get();
        $substatuses = ListStatus::select('id','name','color','type')->where('is_active',1)->where('type','Status')->withCount('status')->get();
    
        return [
            'statuses' => $statuses,
            'substatuses' => $substatuses
        ];
    }

    public function checking($request){
        $scholar = $this->enrolled($request);

        return [
            [
                'name' => 'Completed Grades',
                'completed' => ScholarEnrollment::whereHas('semester',function ($query){
                    $query->where('is_active',1);
                })->where('is_grades_completed',1)->count(),
                'count' => $scholar
            ],
            [
                'name' => 'Released Benefits',
                'completed' => ScholarEnrollment::whereHas('semester',function ($query){
                    $query->where('is_active',1);
                })->where('is_benefits_released',1)->count(),
                'count' => $scholar
            ],
            [
                'name' => 'Completed Enrollees',
                'completed' => ScholarEnrollment::whereHas('semester',function ($query){
                    $query->where('is_active',1);
                })->where('is_completed',1)->count(),
                'count' => $scholar
            ]
        ];
    }

    public function released(){
        $data = Release::where('status_id',13)->where('is_checked',0)->count();
        return $data;
    }

    public function getSemester($id,$year){
    
        $data = Scholar::where('id',$id)
        ->withWhereHas('education', function ($query) use ($id,$year){
            $query->select('id','scholar_id','school_id')
            ->withWhereHas('school', function ($query) use ($id,$year){
                $query->select('id')
                ->withWhereHas('semesters', function ($query) use ($id,$year){
                    $query->where('year','>=',$year)->where('is_active',0)
                    ->whereDoesntHave('enrollments', function ($query) use ($id){
                        $query->where('scholar_id', $id);
                    });
                });
            });
        })
        ->get();
        // ->toSql();
        // dd($data);
        return $data;
    }
    
}