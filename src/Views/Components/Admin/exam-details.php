<aside class="sticky top-0 mx-6 px-4 pt-16 space-y-4 h-screen bg-gray-50">
    @if($exam !== null)
        <div class="w-full p-4 bg-black rounded-xl shadow-lg shadow-slate-200">
            <div class="flex flex-wrap font-bold text-sm items-center">
                <p class="text-white truncate mr-2 max-w-[34rem]">{{ $exam->course()->name }}</p>
                <p class="text-gray-300">({{ $exam->course()->code }})</p>
            </div>
            <p class="text-white font-bold">{{ $exam->name }}</p>
        </div>

        <div class="w-full p-4 bg-white rounded-xl shadow-lg shadow-slate-200">
            <p class="font-bold">Examiner</p>
            <p>
                {{ $exam->course()->teacher()->first_name }}
                {{ $exam->course()->teacher()->last_name }}
            </p>

            <p class="font-bold mt-4">Exam date</p>
            <p>{{ date('j F Y', strtotime($exam->exam_date)) }}</p>

            <a href="{{ route('grades.manage', [ 'id' => $exam->id ]) }}" class="text-white inline-block bg-secondary hover:bg-secondary-hover focus:ring-4 focus:outline-none focus:ring-primary-300 font-bold rounded-lg text-sm mt-4 px-5 py-2.5 text-center">
                Manage grades
            </a>
        </div>

        @content()
    @endif
</aside>
