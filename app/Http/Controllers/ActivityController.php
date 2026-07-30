<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;


class ActivityController extends Controller
{

 public function index(Request $request)
{

    $search = $request->search;

    $action = $request->action;

    $module = $request->module;

    $from = $request->from;

    $to = $request->to;



    $activities = ActivityLog::with('user')

        ->when($search, function($query) use ($search){

            $query->where(function($q) use ($search){

                $q->where('description','like',"%{$search}%")

                ->orWhere('action','like',"%{$search}%")

                ->orWhere('module','like',"%{$search}%")

                ->orWhereHas('user',function($user) use ($search){

                    $user->where(
                        'name',
                        'like',
                        "%{$search}%"
                    );

                });

            });

        })



        ->when($action, function($query) use ($action){

            $query->where('action',$action);

        })



        ->when($module, function($query) use ($module){

            $query->where('module',$module);

        })



        ->when($from, function($query) use ($from){

            $query->whereDate(
                'created_at',
                '>=',
                $from
            );

        })



        ->when($to, function($query) use ($to){

            $query->whereDate(
                'created_at',
                '<=',
                $to
            );

        })



        ->latest()

        ->paginate(30)

        ->withQueryString();



    $actions = ActivityLog::select('action')
        ->distinct()
        ->pluck('action');



    $modules = ActivityLog::select('module')
        ->distinct()
        ->pluck('module');



    return view(
        'activities.index',
        compact(
            'activities',
            'search',
            'actions',
            'modules',
            'action',
            'module',
            'from',
            'to'
        )
    );

}

}