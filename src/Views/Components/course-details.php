<aside class="sticky top-0 mx-6 px-4 pt-16 space-y-4 h-screen bg-gray-50">
    <div class="w-full p-4 bg-black rounded-xl shadow-lg shadow-slate-200">
            <p class="text-white font-bold">{{ $course->name }}</p>
            <p class="text-gray-300 font-bold">{{ $course->code }}</p>
    </div>

    <div class="w-full p-4 bg-white rounded-xl shadow-lg shadow-slate-200">
        <p class="font-bold">Average grade</p>
        <p>{{ $course->avgGrade() ?? '-' }}</p>

        <p class="font-bold mt-4">Examiner</p>
        <p>
            {{ $course->teacher()->first_name }}
            {{ $course->teacher()->last_name }}
        </p>

        <p class="font-bold mt-4 mb-2">Exams</p>
        <ul class="space-y-4">
            @if(count($course->exams()) > 0)
                @foreach($course->exams() as $exam)
                    <li>
                        @if($exam->grade() !== null)
                            <a href="{{ route('grades', [ 'g' => $exam->grade()->id ]) }}" class="flex">
                                <div class="flex my-auto h-12 w-12 mr-4 rounded-xl bg-secondary">
                                    <p class="text-white text-xl font-bold m-auto">
                                        {{ $exam->grade()->grade }}
                                    </p>
                                </div>
                                <div class="my-auto">
                                    <p class="font-bold">{{ $exam->name }}</p>
                                    <p class="text-sm">{{ date('j F Y', strtotime($exam->exam_date)) }}</p>
                                </div>
                            </a>
                        @else
                            <div class="flex">
                                <div class="flex my-auto h-12 w-12 mr-4 rounded-xl bg-secondary">
                                    <p class="text-white text-xl font-bold m-auto">-</p>
                                </div>
                                <div class="my-auto">
                                    <p class="font-bold">{{ $exam->name }}</p>
                                    <p class="text-sm">{{ date('j F Y', strtotime($exam->exam_date)) }}</p>
                                </div>
                            </div>
                        @endif
                    </li>
                @endforeach
            @else
                <p>There are no exams planned for this course.</p>
            @endif
        </ul>
    </div>
</aside>
