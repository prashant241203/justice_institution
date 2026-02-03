<?php
// check_permission.php
function checkPermission($requiredRole) {
    if(!isset($_SESSION['user_role'])) {
        header("Location: login.php");
        exit;
    }
    
    $userRole = $_SESSION['user_role'];
    $roleHierarchy = [
        'admin' => ['admin', 'judge', 'lawyer', 'clerk', 'analyst'],
        'judge' => ['judge', 'lawyer', 'clerk', 'analyst'],
        'lawyer' => ['lawyer', 'clerk'],
        'clerk' => ['clerk'],
        'analyst' => ['analyst']
    ];
    
    if(!in_array($userRole, $roleHierarchy[$requiredRole] ?? [])) {
        header("Location: access_denied.php");
        exit;
    }
}
?>