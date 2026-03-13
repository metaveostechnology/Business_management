<?php

function makeRequest($method, $url, $data = [], $token = null) {
    global $baseUrl;
    $ch = curl_init($baseUrl . $url);
    
    $headers = ['Accept: application/json'];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    if (!empty($data)) {
        if ($method === 'POST' || $method === 'PUT') {
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'status' => $httpCode,
        'body' => json_decode($response, true)
    ];
}

function logResult($step, $res, $expectedStatus) {
    echo "STEP: $step\n";
    if ($res['status'] === $expectedStatus || (is_array($expectedStatus) && in_array($res['status'], $expectedStatus))) {
        echo "✅ SUCCESS (Status: {$res['status']})\n";
    } else {
        echo "❌ FAILED (Expected: " . (is_array($expectedStatus) ? implode(',', $expectedStatus) : $expectedStatus) . ", Got: {$res['status']})\n";
        echo "Response: " . json_encode($res['body'], JSON_PRETTY_PRINT) . "\n";
    }
    echo "--------------------------------------------------------\n";
}

$baseUrl = 'http://localhost:8000/api';

echo "========================================\n";
echo "       TESTING ROLES API                \n";
echo "========================================\n\n";

// 1. Register Company A
$companyA = [
    'name' => 'Company A',
    'email' => 'compA@example.com',
    'phone' => '1111111111',
    'password' => 'secret123',
    'password_confirmation' => 'secret123'
];
$res = makeRequest('POST', '/register-company', $companyA);
logResult("Register Company A", $res, 201);

// Login Company A
$res = makeRequest('POST', '/company/login', ['email' => 'compA@example.com', 'password' => 'secret123']);
$tokenA = $res['body']['data']['token'] ?? null;
logResult("Login Company A", $res, 200);

if (!$tokenA) {
    die("Failed to get Token A. Exiting.\n");
}

// 2. Create Role (Company A)
$roleA = [
    'name' => 'Manager Role A',
    'description' => 'A manager role for Company A',
    'is_active' => true
];
$res = makeRequest('POST', '/company/roles', $roleA, $tokenA);
logResult("Create Role A", $res, 201);
$roleSlugA = $res['body']['data']['slug'] ?? null;

// 3. List Roles (Company A)
$res = makeRequest('GET', '/company/roles', [], $tokenA);
logResult("List Roles (Company A)", $res, 200);

// 4. Get Role by Slug (Company A)
$res = makeRequest('GET', "/company/roles/{$roleSlugA}", [], $tokenA);
logResult("Show Role A", $res, 200);

// 5. Update Role (Company A)
$updateRoleA = ['name' => 'Senior Manager Role A'];
$res = makeRequest('PUT', "/company/roles/{$roleSlugA}", $updateRoleA, $tokenA);
logResult("Update Role A", $res, 200);
$roleSlugA = $res['body']['data']['slug'] ?? $roleSlugA; // Slug might have changed

// 6. Register Company B & Login
$companyB = [
    'name' => 'Company B',
    'email' => 'compB@example.com',
    'phone' => '2222222222',
    'password' => 'secret123',
    'password_confirmation' => 'secret123'
];
makeRequest('POST', '/register-company', $companyB);
$res = makeRequest('POST', '/company/login', ['email' => 'compB@example.com', 'password' => 'secret123']);
$tokenB = $res['body']['data']['token'] ?? null;
logResult("Login Company B", $res, 200);

// 7. List Roles (Company B) - Should NOT see Role A
$res = makeRequest('GET', '/company/roles', [], $tokenB);
$rolesCountB = count($res['body']['data'] ?? []);
if ($res['status'] === 200 && $rolesCountB === 0) {
    echo "✅ SUCCESS: Company B sees 0 roles (isolation works).\n";
} else {
    echo "❌ FAILED: Company B sees {$rolesCountB} roles. Response: " . json_encode($res['body']) . "\n";
}
echo "--------------------------------------------------------\n";

// 8. Try to Show, Update, Delete Role A using Token B (Should be 404)
$res = makeRequest('GET', "/company/roles/{$roleSlugA}", [], $tokenB);
logResult("Show Role A using Token B (Expect 404)", $res, 404);

$res = makeRequest('PUT', "/company/roles/{$roleSlugA}", ['name' => 'Hacked Name'], $tokenB);
logResult("Update Role A using Token B (Expect 404)", $res, 404);

$res = makeRequest('DELETE', "/company/roles/{$roleSlugA}", [], $tokenB);
logResult("Delete Role A using Token B (Expect 404)", $res, 404);

// 9. Delete Role A using Token A
$res = makeRequest('DELETE', "/company/roles/{$roleSlugA}", [], $tokenA);
logResult("Delete Role A using Token A", $res, 204);

echo "\nDone testing Roles API.\n";
