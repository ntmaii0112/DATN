
<?php
return [
    'limits' => [
        'max_requests_per_user' => (int) env('MAX_BORROW_REQUESTS', 5),
    ],
];
