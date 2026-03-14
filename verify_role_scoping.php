<?php

$baseUrl = 'http://localhost:8000/api';

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

echo "========================================\n";
echo "   TESTING ROLE SCOPING REFINEMENT      \n";
echo "========================================\n\n";

// 1. Setup Company A and B
$companyAData = [
    'name' => 'Company A ' . time(),
    'email' => 'compa_'.time().'@test.com',
    'phone' => '1111111111',
    'password' => 'password',
    'password_confirmation' => 'password'
];
$resA = makeRequest('POST', '/register-company', $companyAData);
logResult("Register Company A", $resA, 201);
$tokenA = makeRequest('POST', '/company/login', ['email' => $companyAData['email'], 'password' => 'password'])['body']['data']['token'];

$companyBData = [
    'name' => 'Company B ' . time(),
    'email' => 'compb_'.time().'@test.com',
    'phone' => '2222222222',
    'password' => 'password',
    'password_confirmation' => 'password'
];
$resB = makeRequest('POST', '/register-company', $companyBData);
logResult("Register Company B", $resB, 201);
$tokenB = makeRequest('POST', '/company/login', ['email' => $companyBData['email'], 'password' => 'password'])['body']['data']['token'];

// Setup Branch for Company A
$branchAData = ['code' => 'BR-A1', 'name' => 'Branch A1'];
$resBranchA = makeRequest('POST', '/company/branches', $branchAData, $tokenA);
$branchAId = $resBranchA['body']['data']['id'];

// 2. Test Scoped Unique Role Name
echo "Testing Scoped Unique Role Name...\n";
$roleName = "Manager";

// Create Role in Company A
$resRoleA = makeRequest('POST', '/company/roles', ['name' => $roleName, 'description' => 'A'], $tokenA);
logResult("Create 'Manager' in Company A", $resRoleA, 201);
$roleAId = $resRoleA['body']['data']['id'];
$roleASlug = $resRoleA['body']['data']['slug'];

// Create same Role in Company B (Should SUCCEED - scoping allows it)
$resRoleB = makeRequest('POST', '/company/roles', ['name' => $roleName, 'description' => 'B'], $tokenB);
logResult("Create 'Manager' in Company B (Scoped Unique)", $resRoleB, 201);
$roleBId = $resRoleB['body']['data']['id'];

// Create duplicate Role in Company A (Should FAIL)
$resRoleA_dup = makeRequest('POST', '/company/roles', ['name' => $roleName, 'description' => 'Dup'], $tokenA);
logResult("Create duplicate 'Manager' in Company A (Should Fail)", $resRoleA_dup, 422);

// 3. Test Scoped Role Assignment to Branch User
echo "Testing Scoped Role Assignment to Branch User...\n";

// Assign Company A's Role to Company A's Branch User (Should SUCCEED)
$branchUserA = [
    'branch_id' => $branchAId,
    'role_id' => $roleAId,
    'name' => 'Test User A',
    'email' => 'usera_'.time().'@test.com',
    'password' => 'password',
    'password_confirmation' => 'password'
];
$resAssignA = makeRequest('POST', '/company/branch-users', $branchUserA, $tokenA);
logResult("Assign Role A to Branch User A", $resAssignA, 201);

// Assign Company B's Role to Company A's Branch User (Should FAIL)
$branchUserA_hack = [
    'branch_id' => $branchAId,
    'role_id' => $roleBId,
    'name' => 'Hack User',
    'email' => 'hack_'.time().'@test.com',
    'password' => 'password',
    'password_confirmation' => 'password'
];
$resAssignHack = makeRequest('POST', '/company/branch-users', $branchUserA_hack, $tokenA);
logResult("Assign Role B to Branch User A (Should Fail)", $resAssignHack, [422, 403]);

// 4. Test Update with Scoped Unique
echo "Testing Update Scoped Unique...\n";
$resUpdateRoleA = makeRequest('PUT', "/company/roles/{$roleASlug}", ['name' => 'Admin'], $tokenA);
logResult("Update Role A to 'Admin'", $resUpdateRoleA, 200);

echo "Verification Complete.\n";
