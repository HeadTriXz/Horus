@layout('app.php')
<div class="flex">
    <div class="w-1/2 p-4 sm:ml-64">
        <h1 class="mb-4 text-2xl font-bold">Recent grades</h1>
        <ul class="space-y-4">
            @if(count($grades) > 0)
                @foreach($grades as $grade)
                    <li class="grades-item">
                        <a href="{{ route('grades', [ 'g' => $grade->id ]) }}" class="flex p-4 space-x-4 bg-white rounded-xl shadow-lg shadow-slate-200">
                            <div class="flex my-auto h-14 w-14 rounded-xl bg-secondary">
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
                <p>You don't have any recent grades.</p>
            @endif
        </ul>

        <h1 class="mt-10 mb-4 text-2xl font-bold">Links</h1>
        <div class="grid grid-cols-2 gap-4">
            <a href="https://digirooster.hanze.nl/" class="flex p-4 space-x-4 bg-white rounded-xl shadow-lg shadow-slate-200">
                <div class="flex my-auto h-14 w-14 rounded-xl bg-secondary">
                    <div class="text-white text-2xl font-bold m-auto">
                        <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 transition duration-75" fill="none" viewBox="0 0 35 35" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21.2804 26.0588L15.905 31.4344C15.0444 32.3067 14.0197 33.0001 12.89 33.4747C11.7603 33.9494 10.5478 34.1958 9.32245 34.1999C8.09709 34.2041 6.88302 33.9657 5.75015 33.4987C4.61727 33.0316 3.58797 32.3451 2.7215 31.4786C1.85504 30.612 1.16852 29.5827 0.701495 28.4498C0.234465 27.3168 -0.0038578 26.1027 0.000246868 24.8773C0.00435154 23.6519 0.250808 22.4394 0.725417 21.3096C1.20003 20.1798 1.89342 19.1551 2.76567 18.2944L4.55633 16.5036C4.78996 16.2615 5.06947 16.0683 5.37856 15.9354C5.68765 15.8024 6.02012 15.7324 6.35657 15.7293C6.69303 15.7262 7.02673 15.7902 7.3382 15.9174C7.64967 16.0447 7.93267 16.2327 8.1707 16.4705C8.40873 16.7084 8.59702 16.9912 8.72457 17.3026C8.85213 17.6139 8.9164 17.9476 8.91364 18.2841C8.91087 18.6205 8.84113 18.9531 8.70848 19.2623C8.57582 19.5715 8.38292 19.8513 8.14101 20.0851L6.34867 21.8793C5.5625 22.6726 5.12258 23.7451 5.12519 24.862C5.12779 25.9789 5.5727 27.0493 6.36255 27.839C7.15241 28.6287 8.2229 29.0733 9.33977 29.0756C10.4566 29.0779 11.5289 28.6376 12.322 27.8512L17.6974 22.4756C18.2786 21.8946 18.6777 21.1566 18.8457 20.3521C19.0138 19.5476 18.9435 18.7115 18.6434 17.9464C18.5353 17.6727 18.4238 17.4193 18.3123 17.1794L17.9457 16.3955C17.4727 15.3481 17.27 14.5423 18.2937 13.5168C19.7667 12.0437 21.1266 12.4255 22.3649 14.2213C23.5951 16.0104 24.1602 18.1738 23.9621 20.3361C23.764 22.4983 22.8152 24.523 21.2804 26.0588ZM31.433 15.9056L29.6424 17.6963C29.1647 18.1582 28.5247 18.4139 27.8603 18.4085C27.1959 18.403 26.5603 18.1368 26.0902 17.6672C25.6202 17.1976 25.3534 16.5621 25.3473 15.8977C25.3412 15.2332 25.5963 14.593 26.0577 14.1149L27.8483 12.3224C28.2518 11.9329 28.5737 11.467 28.7952 10.9518C29.0166 10.4366 29.1333 9.88241 29.1383 9.32162C29.1434 8.76084 29.0367 8.20467 28.8245 7.68556C28.6123 7.16645 28.2988 6.69481 27.9024 6.29815C27.506 5.90149 27.0345 5.58775 26.5156 5.37524C25.9966 5.16274 25.4405 5.05572 24.8798 5.06043C24.319 5.06515 23.7648 5.1815 23.2495 5.4027C22.7342 5.6239 22.2681 5.94552 21.8784 6.34879L16.503 11.7244C15.9218 12.3054 15.5227 13.0434 15.3546 13.8479C15.1866 14.6524 15.2569 15.4885 15.557 16.2536C15.6651 16.5273 15.7766 16.7807 15.8881 17.0206L16.2547 17.8045C16.7277 18.8519 16.9304 19.6594 15.9067 20.6832C14.4336 22.1563 13.0738 21.7745 11.8355 19.9787C10.6053 18.1896 10.0401 16.0262 10.2383 13.8639C10.4364 11.7017 11.3852 9.67698 12.92 8.14122L18.2954 2.76561C19.156 1.89332 20.1807 1.19989 21.3104 0.725256C22.4401 0.250622 23.6526 0.00415701 24.8779 5.17383e-05C26.1033 -0.00405353 27.3174 0.234282 28.4502 0.701336C29.5831 1.16839 30.6124 1.85494 31.4789 2.72144C32.3454 3.58795 33.0319 4.61731 33.4989 5.75024C33.9659 6.88317 34.2042 8.0973 34.2001 9.32272C34.196 10.5481 33.9496 11.7606 33.475 12.8904C33.0004 14.0202 32.3053 15.0449 31.433 15.9056Z" fill="currentColor"/>
                        </svg>
                    </div>
                </div>
                <p class="m-auto font-bold">Digirooster</p>
            </a>
            <a href="https://blackboard.hanze.nl" class="flex p-4 space-x-4 bg-white rounded-xl shadow-lg shadow-slate-200">
                <div class="flex my-auto h-14 w-14 rounded-xl bg-secondary">
                    <div class="text-white text-2xl font-bold m-auto">
                        <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 transition duration-75" fill="none" viewBox="0 0 35 35" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21.2804 26.0588L15.905 31.4344C15.0444 32.3067 14.0197 33.0001 12.89 33.4747C11.7603 33.9494 10.5478 34.1958 9.32245 34.1999C8.09709 34.2041 6.88302 33.9657 5.75015 33.4987C4.61727 33.0316 3.58797 32.3451 2.7215 31.4786C1.85504 30.612 1.16852 29.5827 0.701495 28.4498C0.234465 27.3168 -0.0038578 26.1027 0.000246868 24.8773C0.00435154 23.6519 0.250808 22.4394 0.725417 21.3096C1.20003 20.1798 1.89342 19.1551 2.76567 18.2944L4.55633 16.5036C4.78996 16.2615 5.06947 16.0683 5.37856 15.9354C5.68765 15.8024 6.02012 15.7324 6.35657 15.7293C6.69303 15.7262 7.02673 15.7902 7.3382 15.9174C7.64967 16.0447 7.93267 16.2327 8.1707 16.4705C8.40873 16.7084 8.59702 16.9912 8.72457 17.3026C8.85213 17.6139 8.9164 17.9476 8.91364 18.2841C8.91087 18.6205 8.84113 18.9531 8.70848 19.2623C8.57582 19.5715 8.38292 19.8513 8.14101 20.0851L6.34867 21.8793C5.5625 22.6726 5.12258 23.7451 5.12519 24.862C5.12779 25.9789 5.5727 27.0493 6.36255 27.839C7.15241 28.6287 8.2229 29.0733 9.33977 29.0756C10.4566 29.0779 11.5289 28.6376 12.322 27.8512L17.6974 22.4756C18.2786 21.8946 18.6777 21.1566 18.8457 20.3521C19.0138 19.5476 18.9435 18.7115 18.6434 17.9464C18.5353 17.6727 18.4238 17.4193 18.3123 17.1794L17.9457 16.3955C17.4727 15.3481 17.27 14.5423 18.2937 13.5168C19.7667 12.0437 21.1266 12.4255 22.3649 14.2213C23.5951 16.0104 24.1602 18.1738 23.9621 20.3361C23.764 22.4983 22.8152 24.523 21.2804 26.0588ZM31.433 15.9056L29.6424 17.6963C29.1647 18.1582 28.5247 18.4139 27.8603 18.4085C27.1959 18.403 26.5603 18.1368 26.0902 17.6672C25.6202 17.1976 25.3534 16.5621 25.3473 15.8977C25.3412 15.2332 25.5963 14.593 26.0577 14.1149L27.8483 12.3224C28.2518 11.9329 28.5737 11.467 28.7952 10.9518C29.0166 10.4366 29.1333 9.88241 29.1383 9.32162C29.1434 8.76084 29.0367 8.20467 28.8245 7.68556C28.6123 7.16645 28.2988 6.69481 27.9024 6.29815C27.506 5.90149 27.0345 5.58775 26.5156 5.37524C25.9966 5.16274 25.4405 5.05572 24.8798 5.06043C24.319 5.06515 23.7648 5.1815 23.2495 5.4027C22.7342 5.6239 22.2681 5.94552 21.8784 6.34879L16.503 11.7244C15.9218 12.3054 15.5227 13.0434 15.3546 13.8479C15.1866 14.6524 15.2569 15.4885 15.557 16.2536C15.6651 16.5273 15.7766 16.7807 15.8881 17.0206L16.2547 17.8045C16.7277 18.8519 16.9304 19.6594 15.9067 20.6832C14.4336 22.1563 13.0738 21.7745 11.8355 19.9787C10.6053 18.1896 10.0401 16.0262 10.2383 13.8639C10.4364 11.7017 11.3852 9.67698 12.92 8.14122L18.2954 2.76561C19.156 1.89332 20.1807 1.19989 21.3104 0.725256C22.4401 0.250622 23.6526 0.00415701 24.8779 5.17383e-05C26.1033 -0.00405353 27.3174 0.234282 28.4502 0.701336C29.5831 1.16839 30.6124 1.85494 31.4789 2.72144C32.3454 3.58795 33.0319 4.61731 33.4989 5.75024C33.9659 6.88317 34.2042 8.0973 34.2001 9.32272C34.196 10.5481 33.9496 11.7606 33.475 12.8904C33.0004 14.0202 32.3053 15.0449 31.433 15.9056Z" fill="currentColor"/>
                        </svg>
                    </div>
                </div>
                <p class="m-auto font-bold">Blackboard Learn</p>
            </a>
            <a href="https://hanze.nl/webmail" class="flex p-4 space-x-4 bg-white rounded-xl shadow-lg shadow-slate-200">
                <div class="flex my-auto h-14 w-14 rounded-xl bg-secondary">
                    <div class="text-white text-2xl font-bold m-auto">
                        <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 transition duration-75" fill="none" viewBox="0 0 35 35" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21.2804 26.0588L15.905 31.4344C15.0444 32.3067 14.0197 33.0001 12.89 33.4747C11.7603 33.9494 10.5478 34.1958 9.32245 34.1999C8.09709 34.2041 6.88302 33.9657 5.75015 33.4987C4.61727 33.0316 3.58797 32.3451 2.7215 31.4786C1.85504 30.612 1.16852 29.5827 0.701495 28.4498C0.234465 27.3168 -0.0038578 26.1027 0.000246868 24.8773C0.00435154 23.6519 0.250808 22.4394 0.725417 21.3096C1.20003 20.1798 1.89342 19.1551 2.76567 18.2944L4.55633 16.5036C4.78996 16.2615 5.06947 16.0683 5.37856 15.9354C5.68765 15.8024 6.02012 15.7324 6.35657 15.7293C6.69303 15.7262 7.02673 15.7902 7.3382 15.9174C7.64967 16.0447 7.93267 16.2327 8.1707 16.4705C8.40873 16.7084 8.59702 16.9912 8.72457 17.3026C8.85213 17.6139 8.9164 17.9476 8.91364 18.2841C8.91087 18.6205 8.84113 18.9531 8.70848 19.2623C8.57582 19.5715 8.38292 19.8513 8.14101 20.0851L6.34867 21.8793C5.5625 22.6726 5.12258 23.7451 5.12519 24.862C5.12779 25.9789 5.5727 27.0493 6.36255 27.839C7.15241 28.6287 8.2229 29.0733 9.33977 29.0756C10.4566 29.0779 11.5289 28.6376 12.322 27.8512L17.6974 22.4756C18.2786 21.8946 18.6777 21.1566 18.8457 20.3521C19.0138 19.5476 18.9435 18.7115 18.6434 17.9464C18.5353 17.6727 18.4238 17.4193 18.3123 17.1794L17.9457 16.3955C17.4727 15.3481 17.27 14.5423 18.2937 13.5168C19.7667 12.0437 21.1266 12.4255 22.3649 14.2213C23.5951 16.0104 24.1602 18.1738 23.9621 20.3361C23.764 22.4983 22.8152 24.523 21.2804 26.0588ZM31.433 15.9056L29.6424 17.6963C29.1647 18.1582 28.5247 18.4139 27.8603 18.4085C27.1959 18.403 26.5603 18.1368 26.0902 17.6672C25.6202 17.1976 25.3534 16.5621 25.3473 15.8977C25.3412 15.2332 25.5963 14.593 26.0577 14.1149L27.8483 12.3224C28.2518 11.9329 28.5737 11.467 28.7952 10.9518C29.0166 10.4366 29.1333 9.88241 29.1383 9.32162C29.1434 8.76084 29.0367 8.20467 28.8245 7.68556C28.6123 7.16645 28.2988 6.69481 27.9024 6.29815C27.506 5.90149 27.0345 5.58775 26.5156 5.37524C25.9966 5.16274 25.4405 5.05572 24.8798 5.06043C24.319 5.06515 23.7648 5.1815 23.2495 5.4027C22.7342 5.6239 22.2681 5.94552 21.8784 6.34879L16.503 11.7244C15.9218 12.3054 15.5227 13.0434 15.3546 13.8479C15.1866 14.6524 15.2569 15.4885 15.557 16.2536C15.6651 16.5273 15.7766 16.7807 15.8881 17.0206L16.2547 17.8045C16.7277 18.8519 16.9304 19.6594 15.9067 20.6832C14.4336 22.1563 13.0738 21.7745 11.8355 19.9787C10.6053 18.1896 10.0401 16.0262 10.2383 13.8639C10.4364 11.7017 11.3852 9.67698 12.92 8.14122L18.2954 2.76561C19.156 1.89332 20.1807 1.19989 21.3104 0.725256C22.4401 0.250622 23.6526 0.00415701 24.8779 5.17383e-05C26.1033 -0.00405353 27.3174 0.234282 28.4502 0.701336C29.5831 1.16839 30.6124 1.85494 31.4789 2.72144C32.3454 3.58795 33.0319 4.61731 33.4989 5.75024C33.9659 6.88317 34.2042 8.0973 34.2001 9.32272C34.196 10.5481 33.9496 11.7606 33.475 12.8904C33.0004 14.0202 32.3053 15.0449 31.433 15.9056Z" fill="currentColor"/>
                        </svg>
                    </div>
                </div>
                <p class="m-auto font-bold">Outlook</p>
            </a>
            <a href="https://mijnhanze.nl" class="flex p-4 space-x-4 bg-white rounded-xl shadow-lg shadow-slate-200">
                <div class="flex my-auto h-14 w-14 rounded-xl bg-secondary">
                    <div class="text-white text-2xl font-bold m-auto">
                        <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 transition duration-75" fill="none" viewBox="0 0 35 35" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21.2804 26.0588L15.905 31.4344C15.0444 32.3067 14.0197 33.0001 12.89 33.4747C11.7603 33.9494 10.5478 34.1958 9.32245 34.1999C8.09709 34.2041 6.88302 33.9657 5.75015 33.4987C4.61727 33.0316 3.58797 32.3451 2.7215 31.4786C1.85504 30.612 1.16852 29.5827 0.701495 28.4498C0.234465 27.3168 -0.0038578 26.1027 0.000246868 24.8773C0.00435154 23.6519 0.250808 22.4394 0.725417 21.3096C1.20003 20.1798 1.89342 19.1551 2.76567 18.2944L4.55633 16.5036C4.78996 16.2615 5.06947 16.0683 5.37856 15.9354C5.68765 15.8024 6.02012 15.7324 6.35657 15.7293C6.69303 15.7262 7.02673 15.7902 7.3382 15.9174C7.64967 16.0447 7.93267 16.2327 8.1707 16.4705C8.40873 16.7084 8.59702 16.9912 8.72457 17.3026C8.85213 17.6139 8.9164 17.9476 8.91364 18.2841C8.91087 18.6205 8.84113 18.9531 8.70848 19.2623C8.57582 19.5715 8.38292 19.8513 8.14101 20.0851L6.34867 21.8793C5.5625 22.6726 5.12258 23.7451 5.12519 24.862C5.12779 25.9789 5.5727 27.0493 6.36255 27.839C7.15241 28.6287 8.2229 29.0733 9.33977 29.0756C10.4566 29.0779 11.5289 28.6376 12.322 27.8512L17.6974 22.4756C18.2786 21.8946 18.6777 21.1566 18.8457 20.3521C19.0138 19.5476 18.9435 18.7115 18.6434 17.9464C18.5353 17.6727 18.4238 17.4193 18.3123 17.1794L17.9457 16.3955C17.4727 15.3481 17.27 14.5423 18.2937 13.5168C19.7667 12.0437 21.1266 12.4255 22.3649 14.2213C23.5951 16.0104 24.1602 18.1738 23.9621 20.3361C23.764 22.4983 22.8152 24.523 21.2804 26.0588ZM31.433 15.9056L29.6424 17.6963C29.1647 18.1582 28.5247 18.4139 27.8603 18.4085C27.1959 18.403 26.5603 18.1368 26.0902 17.6672C25.6202 17.1976 25.3534 16.5621 25.3473 15.8977C25.3412 15.2332 25.5963 14.593 26.0577 14.1149L27.8483 12.3224C28.2518 11.9329 28.5737 11.467 28.7952 10.9518C29.0166 10.4366 29.1333 9.88241 29.1383 9.32162C29.1434 8.76084 29.0367 8.20467 28.8245 7.68556C28.6123 7.16645 28.2988 6.69481 27.9024 6.29815C27.506 5.90149 27.0345 5.58775 26.5156 5.37524C25.9966 5.16274 25.4405 5.05572 24.8798 5.06043C24.319 5.06515 23.7648 5.1815 23.2495 5.4027C22.7342 5.6239 22.2681 5.94552 21.8784 6.34879L16.503 11.7244C15.9218 12.3054 15.5227 13.0434 15.3546 13.8479C15.1866 14.6524 15.2569 15.4885 15.557 16.2536C15.6651 16.5273 15.7766 16.7807 15.8881 17.0206L16.2547 17.8045C16.7277 18.8519 16.9304 19.6594 15.9067 20.6832C14.4336 22.1563 13.0738 21.7745 11.8355 19.9787C10.6053 18.1896 10.0401 16.0262 10.2383 13.8639C10.4364 11.7017 11.3852 9.67698 12.92 8.14122L18.2954 2.76561C19.156 1.89332 20.1807 1.19989 21.3104 0.725256C22.4401 0.250622 23.6526 0.00415701 24.8779 5.17383e-05C26.1033 -0.00405353 27.3174 0.234282 28.4502 0.701336C29.5831 1.16839 30.6124 1.85494 31.4789 2.72144C32.3454 3.58795 33.0319 4.61731 33.4989 5.75024C33.9659 6.88317 34.2042 8.0973 34.2001 9.32272C34.196 10.5481 33.9496 11.7606 33.475 12.8904C33.0004 14.0202 32.3053 15.0449 31.433 15.9056Z" fill="currentColor"/>
                        </svg>
                    </div>
                </div>
                <p class="m-auto font-bold">mijnhanze.nl</p>
            </a>
        </div>
    </div>
    <div class="w-1/2">
        <aside class="sticky top-0 ml-32 px-6 h-screen bg-gray-100">
            <div class="bg-gray-50 px-4 pt-16 h-screen space-y-4">
                <h1 class="mb-4 text-2xl font-bold">Upcoming exams</h1>
                @if(empty($exams))
                    <p>There are no upcoming exams.</p>
                @else
                    <ul class="space-y-4 overflow-scroll h-2/3">
                        @foreach($exams as $exam)
                            <li>
                                <div class="p-4 text-white bg-black rounded-xl shadow-lg shadow-slate-200">
                                    <p class="font-bold">
                                        {{ $exam->course()->name }} ({{ $exam->course()->code }})
                                    </p>
                                    <p class="text-sm">
                                        {{ $exam->name }}
                                    </p>
                                    <p class="text-sm">
                                        {{ date('j F Y', strtotime($exam->exam_date)) }}
                                        @if($exam->duration !== null)
                                            ({{ date('H:i', strtotime($exam->exam_date)) }}
                                                -
                                            {{ date('H:i', strtotime($exam->endsAt())) }})
                                        @endif
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <div>
                    <h1 class="mb-4 text-2xl font-bold">Help</h1>
                    <a href="https://letmegooglethat.com/?q=How+do+I+use+Horus%3F" class="flex p-8 space-x-4 bg-secondary rounded-xl shadow-lg shadow-slate-200">
                        <p class="m-auto text-white font-bold">Help Centre</p>
                        <div class="text-white text-2xl font-bold m-auto">
                            <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 transition duration-75" fill="none" viewBox="0 0 28 28" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M25.3798 25.38C27.3332 23.4293 27.3332 20.2853 27.3332 14C27.3332 7.71463 27.3332 4.57196 25.3798 2.61863C23.4292 0.666626 20.2852 0.666626 13.9998 0.666626C7.7145 0.666626 4.57184 0.666626 2.6185 2.61863C0.666504 4.57329 0.666504 7.71463 0.666504 14C0.666504 20.2853 0.666504 23.428 2.6185 25.38C4.57317 27.3333 7.7145 27.3333 13.9998 27.3333C20.2852 27.3333 23.4278 27.3333 25.3798 25.38ZM17.7705 9.22929C18.0357 9.22929 18.2901 9.33465 18.4776 9.52219C18.6651 9.70972 18.7705 9.96408 18.7705 10.2293V15.8853C18.7705 16.1505 18.6651 16.4049 18.4776 16.5924C18.2901 16.7799 18.0357 16.8853 17.7705 16.8853C17.5053 16.8853 17.2509 16.7799 17.0634 16.5924C16.8759 16.4049 16.7705 16.1505 16.7705 15.8853V12.6426L10.9358 18.48C10.8443 18.5782 10.7339 18.657 10.6112 18.7117C10.4886 18.7663 10.3561 18.7957 10.2219 18.7981C10.0876 18.8004 9.95422 18.7757 9.82971 18.7255C9.70519 18.6752 9.59208 18.6003 9.49712 18.5053C9.40216 18.4104 9.3273 18.2973 9.27701 18.1728C9.22671 18.0482 9.20201 17.9149 9.20438 17.7806C9.20675 17.6463 9.23614 17.5139 9.2908 17.3912C9.34545 17.2686 9.42425 17.1582 9.5225 17.0666L15.3572 11.2306H12.1145C11.8493 11.2306 11.5949 11.1253 11.4074 10.9377C11.2199 10.7502 11.1145 10.4958 11.1145 10.2306C11.1145 9.96541 11.2199 9.71105 11.4074 9.52352C11.5949 9.33598 11.8493 9.23063 12.1145 9.23063H17.7705V9.22929Z" fill="currentColor"/>
                            </svg>
                        </div>
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>
@endlayout
