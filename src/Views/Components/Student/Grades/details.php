<aside class="sticky top-0 mx-6 px-4 pt-16 space-y-4 h-screen bg-gray-50">
    @if($grade !== null)
        <div class="w-full p-4 bg-black rounded-xl shadow-lg shadow-slate-200">
            <p class="text-sm text-white">{{ $grade->exam()->name }}</p>
            <p class="text-sm text-white">{{ date('j F Y', strtotime($grade->exam()->exam_date)) }}</p>
            <div class="flex flex-wrap mt-2 items-center font-bold">
                <p class="text-white truncate mr-2 max-w-xs">{{ $grade->exam()->course()->name }}</p>
                <p class="text-gray-300">({{ $grade->exam()->course()->code }})</p>
            </div>
        </div>

        <div class="w-full p-4 bg-white rounded-xl shadow-lg shadow-slate-200">
            <p class="font-bold">Exam</p>
            <p>{{ $grade->exam()->name }}</p>

            <p class="font-bold mt-4">My grade</p>
            <p>{{ $grade->grade }}</p>

            <p class="font-bold mt-4">Examiner</p>
            <p>
                {{ $grade->exam()->course()->teacher()->first_name }}
                {{ $grade->exam()->course()->teacher()->last_name }}
            </p>
        </div>

        <div class="w-full p-4 bg-white rounded-xl shadow-lg shadow-slate-200">
            <p class="font-bold">Exam date</p>
            <p>{{ date('j F Y', strtotime($grade->exam()->exam_date)) }}</p>

            <p class="font-bold mt-4">Last update</p>
            <p>{{ date('j F Y', strtotime($grade->updated_at)) }}</p>
        </div>
    @endif
</aside>
