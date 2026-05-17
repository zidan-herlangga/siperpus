<?php

return [
    'fine_per_day' => env('LIBRARY_FINE_PER_DAY', 1000),
    'borrow_duration_days' => env('LIBRARY_BORROW_DURATION_DAYS', 7),
    'max_borrow_per_student' => env('LIBRARY_MAX_BORROW_PER_STUDENT', 3),
];
