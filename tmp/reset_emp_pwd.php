<?php

use App\Models\BranchUser;
use Illuminate\Support\Facades\Hash;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'a11@gmail.com';
$user = BranchUser::where('email', $email)->first();

if ($user) {
    $user->password = Hash::make('password123');
    $user->save();
    echo "Password reset for $email to 'password123'\n";
} else {
    echo "User $email not found.\n";
}
