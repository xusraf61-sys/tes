@extends('layouts.app')

@section('content')

<div class="space-y-8 p-4 md:p-10 bg-gradient-to-r from-purple-100 via-pink-50 to-orange-50 min-h-screen">

    <!-- TOP CARDS (Timer + Search) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- TIMER BOX -->
        <div class="relative overflow-hidden p-6 rounded-3xl shadow-2xl text-white flex flex-col md:flex-row items-center justify-between bg-gradient-to-br from-indigo-600 via-purple-500 to-pink-500 animate-gradient-x">
            
            <div>
                <div class="text-lg opacity-90 font-semibold">Study Timer</div>
                <div id="timer" class="text-5xl md:text-6xl font-mono font-bold mt-2 drop-shadow-lg">00:00:00</div>
            </div>

            <div class="flex gap-3 mt-4 md:mt-0">
                <button id="startBtn" class="flex items-center gap-2 px-5 py-3 rounded-2xl bg-green-500 hover:bg-green-600 shadow-lg transition-all transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-6.518-3.759A1 1 0 007 8.24v7.52a1 1 0 001.234.97l6.518-1.76a1 1 0 00.5-.282 1 1 0 00.5-.87 1 1 0 00-.5-.87z"/>
                    </svg>
                    Start
                </button>
                <button id="pauseBtn" class="px-5 py-3 rounded-2xl bg-yellow-400 hover:bg-yellow-500 shadow-lg transition-all transform hover:scale-105">Pause</button>
                <button id="finishBtn" class="px-5 py-3 rounded-2xl bg-red-500 hover:bg-red-600 shadow-lg transition-all transform hover:scale-105">Finish</button>
            </div>
        </div>

        <!-- SEARCH DATE -->
        <div class="bg-white p-6 rounded-3xl shadow-xl flex items-center gap-4 border-l-4 border-orange-400">
            <div class="flex-1">
                <label class="text-gray-700 font-medium">Select Date</label>
                <input id="searchDate" type="date"
                    class="mt-2 border border-gray-300 px-4 py-3 rounded-2xl w-full focus:ring-2 focus:ring-orange-500 outline-none transition shadow-sm hover:shadow-md">
            </div>
            <button id="searchBtn"
                    class="flex items-center gap-2 px-5 py-3 rounded-2xl bg-orange-500 hover:bg-orange-600 shadow-lg text-white font-semibold transition transform hover:scale-105">
                Go
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7l7 7-7 7"/>
                </svg>
            </button>
        </div>

    </div>

    <!-- ADD EXAMPLES CARD -->
    <div class="bg-white p-6 rounded-3xl shadow-xl border-t-4 border-orange-400">
        <h3 class="font-semibold text-2xl mb-4 text-gray-800">Add solved examples</h3>

        <div class="flex items-center gap-4">
            <input id="examplesInput" type="number" min="1"
                class="border border-gray-300 px-4 py-3 rounded-2xl w-48 focus:ring-2 focus:ring-orange-500 outline-none transition shadow-sm hover:shadow-md"
                placeholder="Nechta misol?">

            <button id="addExamplesBtn"
                class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-orange-500 hover:bg-orange-600 shadow-lg text-white font-medium transition transform hover:scale-105">
                Add
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- DAY INFO -->
    <div id="dayInfo" class="text-gray-800 font-semibold text-xl md:text-2xl bg-white p-6 rounded-3xl shadow-xl text-center border-t-4 border-indigo-500">
        <!-- JS orqali to‘ladi -->
    </div>

</div>

<!-- JAVASCRIPT -->
<script>
let timerInterval = null;
let currentDay = "{{ date('Y-m-d') }}";
let elapsedSeconds = 0;

window.addEventListener("load", () => {
    let saved = localStorage.getItem("savedDate");

    if(saved){
        document.getElementById("searchDate").value = saved;
        currentDay = saved;
    } else {
        let today = new Date().toISOString().split("T")[0];
        document.getElementById("searchDate").value = today;
        currentDay = today;
    }

    loadToday();
});

function getDayData(date){
    return JSON.parse(localStorage.getItem("day_"+date)) || { examples: 0, seconds: 0 };
}

function saveDayData(date, data){
    localStorage.setItem("day_"+date, JSON.stringify(data));
}

function loadToday(){
    let data = getDayData(currentDay);
    elapsedSeconds = data.seconds || 0;
    renderTimer();

    document.getElementById('dayInfo').textContent =
        `Today solved: ${data.examples} ta test`;
}

function formatHMS(sec){
    const h = String(Math.floor(sec/3600)).padStart(2,'0');
    const m = String(Math.floor((sec%3600)/60)).padStart(2,'0');
    const s = String(sec%60).padStart(2,'0');
    return `${h}:${m}:${s}`;
}

function renderTimer(){
    document.getElementById('timer').textContent = formatHMS(elapsedSeconds);
}

document.getElementById('startBtn').addEventListener('click', ()=>{
    if(timerInterval) return;

    timerInterval = setInterval(()=>{
        elapsedSeconds++;
        let data = getDayData(currentDay);
        data.seconds = elapsedSeconds;
        saveDayData(currentDay, data);
        renderTimer();
    }, 1000);
});

document.getElementById('pauseBtn').addEventListener('click', ()=>{
    clearInterval(timerInterval);
    timerInterval = null;
});

document.getElementById('finishBtn').addEventListener('click', ()=>{
    clearInterval(timerInterval);
    timerInterval = null;

    let data = getDayData(currentDay);
    data.seconds = 0;
    saveDayData(currentDay, data);

    elapsedSeconds = 0;
    renderTimer();
});

document.getElementById('searchBtn').addEventListener('click', ()=>{
    const val = document.getElementById('searchDate').value;
    if(!val) return alert("Sana tanlang!");
    localStorage.setItem("savedDate", val);
    currentDay = val;
    loadToday();
});

document.getElementById('addExamplesBtn').addEventListener('click', ()=>{
    const v = parseInt(document.getElementById('examplesInput').value || '0');
    if(!v) return alert("Musbat son kiriting!");

    let data = getDayData(currentDay);
    data.examples += v;
    saveDayData(currentDay, data);

    document.getElementById('dayInfo').textContent =
        `Today solved: ${data.examples} ta test`;
});
</script>

@endsection
