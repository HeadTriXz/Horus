@layout('app.php')
<div class="flex">
    <div class="w-1/2 p-4 sm:ml-64">
        <h1 class="mb-4 text-2xl font-black">Your grades</h1>
        <ul class="space-y-4">
            @if(count($grades) > 0)
                @foreach($grades as $grade)
                    <li>
                        <a href="{{ route('grades', [ 'g' => $grade->id ]) }}" class="flex w-full p-4 space-x-4 bg-white rounded-xl shadow-lg shadow-slate-200">
                            <?php
                                $bgColor = $grade->id === $selectedGrade?->id
                                    ? "bg-black"
                                    : "bg-secondary";
                            ?>
                            <div class="flex my-auto h-14 w-14 rounded-xl {{ $bgColor }}">
                                <p class="text-white text-2xl font-bold m-auto">{{ $grade->grade }}</p>
                            </div>
                            <div>
                                <div class="flex flex-wrap items-center font-bold">
                                    <p class="truncate mr-2 max-w-[34rem]">{{ $grade->exam()->course()->name }}</p>
                                    <p class="text-gray-600">({{ $grade->exam()->course()->code }})</p>
                                </div>

                                <p class="text-sm">
                                    {{ $grade->exam()->name }}
                                </p>
                                <p class="text-sm">
                                    {{ date('j F Y', strtotime($grade->created_at)) }}
                                </p>
                            </div>
                        </a>
                    </li>
                @endforeach
            @else
                <p>You don't have any grades.</p>
            @endif
        </ul>
    </div>
    <div class="w-1/2 bg-gray-100">
        @component('grade-details.php', [ 'grade' => $selectedGrade ]) @endcomponent
    </div>
</div>
@endlayout
