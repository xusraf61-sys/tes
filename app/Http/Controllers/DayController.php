<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Day;
use Illuminate\Support\Facades\Auth;

class DayController extends Controller
{
    public function index() {
        $days = Auth::user()->days()->orderBy('date','desc')->get();
        return view('dashboard', compact('days'));
    }

    public function show($date) {
        $day = Auth::user()->days()->where('date', $date)->firstOrCreate(['date' => $date]);
        return response()->json($day);
    }

    public function createDay(Request $req){
        $data = $req->validate(['date'=>'required|date']);
        $day = Auth::user()->days()->firstOrCreate(['date'=>$data['date']]);
        return response()->json($day, 201);
    }

    public function addSeconds(Request $req){
        $v = $req->validate(['date'=>'required|date','seconds'=>'required|integer|min:1']);
        $day = Auth::user()->days()->firstOrCreate(['date'=>$v['date']]);
        $day->increment('study_seconds', $v['seconds']);
        return response()->json($day);
    }

    public function addExamples(Request $req){
        $v = $req->validate(['date'=>'required|date','count'=>'required|integer|min:1']);
        $day = Auth::user()->days()->firstOrCreate(['date'=>$v['date']]);
        $day->increment('examples_solved', $v['count']);
        return response()->json($day);
    }
}
