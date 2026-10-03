<?php

return [
    'daily_transaction_target' => max(1, (int) env('DAILY_TRANSACTION_TARGET', 120)),
];
