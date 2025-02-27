<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ActivityController extends Controller
{
    public function index()
    {
        return view('activity'); // Buat tampilan ini
    }

    public function getActivities(Request $request)
    {
        if ($request->ajax()) {
            $data = Activity::with('status')->get(); // Ambil data dengan relasi status
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row) {
                    return $row->status ? $row->status->nama_status : 'Unknown'; // Ambil nama status, jika ada
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                    $btn .= ' <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

   
    public function storeMultiple(Request $request)
    {
        $request->validate([
            'data' => 'required|array',
            'data.*.nama_aktivitas' => 'required|string|max:255',
            'data.*.masalah' => 'required|string|max:500',
        ]);

        $tasks = [];
        foreach ($request->data as $task) {
            $tasks[] = [
                'nama_aktivitas' => $task['nama_aktivitas'],
                'masalah' => $task['masalah'],
                'status_id' => 4, // Default status
                'solusi' => '-',
                'information' => '-',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Activity::insert($tasks);

        return response()->json(['message' => 'Semua tugas berhasil ditambahkan!'], 201);
    }


}