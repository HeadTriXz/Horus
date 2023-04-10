@layout('app.php')
<div class="flex">
    <div class="w-1/2 p-4 sm:ml-64">
        <h1 class="mb-4 text-2xl font-black">Your courses</h1>
        <ul class="space-y-4">
            @if(count($courses) > 0)
                @foreach($courses as $course)
                    <li>
                        <a href="{{ route('courses', [ 'c' => $course->id ]) }}" class="flex w-full p-4 space-x-4 bg-white rounded-xl shadow-lg shadow-slate-200">
                            <?php
                                $bgColor = $course->id === $selectedCourse?->id
                                    ? "bg-black"
                                    : "bg-secondary";
                            ?>
                            <div class="flex my-auto h-14 w-14 rounded-xl {{ $bgColor }}">
                                <p class="text-white text-2xl font-bold m-auto">
                                    {{ $course->avgGrade() ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="font-bold">{{ $course->name }}</p>
                                <p class="text-sm">{{ $course->code }}</p>
                                <p class="text-sm">
                                    <?php
                                        $exams = $course->exams();
                                        $latestGrade = null;
                                        foreach ($exams as $exam) {
                                            $date = $exam->grade()?->created_at;
                                            if ($latestGrade === null || $date !== null && $date > $latestGrade) {
                                                $latestGrade = $date;
                                            }
                                        }

                                        if ($latestGrade !== null) {
                                            echo date('j F Y', strtotime($latestGrade));
                                        } else {
                                            echo "Enrolled";
                                        }
                                    ?>
                                </>
                            </div>
                        </a>
                    </li>
                @endforeach
            @else
                <p>You don't have any courses.</p>
            @endif
        </ul>
    </div>
    <div class="w-1/2 bg-gray-100">
        @component('course-details.php', [ 'course' => $selectedCourse ]) @endcomponent
    </div>
</div>
@endlayout
