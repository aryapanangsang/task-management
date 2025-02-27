<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use DataTables;

class ActivityController extends Controller
{
    public function index()
    {
        return view('activity'); // Buat tampilan ini
    }

    public function getActivities(Request $request)
    {
        if ($request->ajax()) {
            $data = Activity::all();
            return DataTables::of($data)
                ->addIndexColumn() // Tambahkan kolom index otomatis
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                    $btn .= ' <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}