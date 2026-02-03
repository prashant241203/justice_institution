<?php
// File: auth_check.php
// Location: justice_project/auth_check.php

/**
 * Authentication and Authorization Check System
 * Include this file at the top of every protected page
 */

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ============================================
// SECURITY CHECK FUNCTIONS
// ============================================

/**
 * Check if user is logged in
 */
function checkLogin() {
    if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header("Location: login.php");
        exit;
    }
}

/**
 * Check if user has specific role(s)
 */
function checkRole($required_roles = []) {
    if(empty($required_roles)) {
        return true; // No role restriction
    }
    
    if(!isset($_SESSION['user_role'])) {
        header("Location: login.php");
        exit;
    }
    
    if(!in_array($_SESSION['user_role'], $required_roles)) {
        header("Location: access_denied.php");
        exit;
    }
    
    return true;
}

/**
 * Check permission for current page
 */
function checkPagePermission() {
    $current_page = basename($_SERVER['PHP_SELF']);
    
    // Define page permissions
    $page_permissions = [
        // Admin only pages
        'admin_panel.php' => ['admin'],
        'manage_users.php' => ['admin'],
        'system_settings.php' => ['admin'],
        
        // Judge only pages  
        'judge_panel.php' => ['judge'],
        'my_judgements.php' => ['judge'],
        'court_schedule.php' => ['judge'],
        
        // Judge and Admin pages
        'add_judgement.php' => ['judge', 'admin'],
        'financial_reports.php' => ['admin', 'judge'],
        
        // Clerk, Lawyer, Judge, Admin pages
        'add_hearing.php' => ['clerk', 'lawyer', 'judge', 'admin'],
        'add_case.php' => ['clerk', 'admin'],
        
        // All logged in users
        'index.php' => ['admin', 'judge', 'lawyer', 'clerk', 'analyst'],
        'view_case.php' => ['admin', 'judge', 'lawyer', 'clerk', 'analyst'],
        'search.php' => ['admin', 'judge', 'lawyer', 'clerk', 'analyst'],
        'profile.php' => ['admin', 'judge', 'lawyer', 'clerk', 'analyst'],
    ];
    
    // Check if page exists in permissions list
    if(isset($page_permissions[$current_page])) {
        $allowed_roles = $page_permissions[$current_page];
        checkRole($allowed_roles);
    }
}

/**
 * Get current user info
 */
function getUserInfo() {
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'name' => $_SESSION['user_name'] ?? 'Guest',
        'email' => $_SESSION['user_email'] ?? null,
        'role' => $_SESSION['user_role'] ?? 'guest'
    ];
}

/**
 * Get role badge with color
 */
function getRoleBadge($role = null) {
    if($role === null) {
        $role = $_SESSION['user_role'] ?? 'guest';
    }
    
    $role_colors = [
        'admin' => 'danger',
        'judge' => 'success',
        'lawyer' => 'primary',
        'clerk' => 'warning',
        'analyst' => 'info',
        'guest' => 'secondary'
    ];
    
    $color = $role_colors[$role] ?? 'secondary';
    $role_name = ucfirst($role);
    
    return "<span class='badge bg-$color'><i class='bi bi-shield-check'></i> $role_name</span>";
}

/**
 * Check if user can perform action
 */
function can($action) {
    $user_role = $_SESSION['user_role'] ?? 'guest';
    
    $permissions = [
        'add_case' => ['admin', 'clerk'],
        'edit_case' => ['admin', 'clerk'],
        'delete_case' => ['admin'],
        'add_hearing' => ['admin', 'clerk', 'lawyer'],
        'add_judgement' => ['admin', 'judge'],
        'view_financial' => ['admin', 'judge'],
        'manage_users' => ['admin'],
        'view_audit_logs' => ['admin'],
        'export_data' => ['admin', 'analyst'],
    ];
    
    return isset($permissions[$action]) && in_array($user_role, $permissions[$action]);
}

// ============================================
// AUTO-RUN SECURITY CHECKS
// ============================================

// List of pages that don't need authentication
$public_pages = ['login.php', 'logout.php', 'register.php', 'access_denied.php'];

// Get current page
$current_page = basename($_SERVER['PHP_SELF']);

// If not a public page, run security checks
if(!in_array($current_page, $public_pages)) {
    checkLogin();
    checkPagePermission();
}

// ============================================
// HELPER FUNCTIONS
// ============================================

/**
 * Redirect if not logged in
 */
function requireLogin() {
    checkLogin();
}

/**
 * Redirect if not specific role
 */
function requireRole($role) {
    checkRole([$role]);
}

/**
 * Redirect if not in roles list
 */
function requireRoles($roles) {
    checkRole($roles);
}

/**
 * Check if current user is admin
 */
function isAdmin() {
    return ($_SESSION['user_role'] ?? '') === 'admin';
}

/**
 * Check if current user is judge
 */
function isJudge() {
    return ($_SESSION['user_role'] ?? '') === 'judge';
}

/**
 * Check if current user is lawyer
 */
function isLawyer() {
    return ($_SESSION['user_role'] ?? '') === 'lawyer';
}

/**
 * Check if current user is clerk
 */
function isClerk() {
    return ($_SESSION['user_role'] ?? '') === 'clerk';
}

/**
 * Get greeting based on role
 */
function getGreeting() {
    $user = getUserInfo();
    $role = $user['role'];
    
    $greetings = [
        'admin' => 'System Administrator',
        'judge' => 'Your Honor',
        'lawyer' => 'Advocate',
        'clerk' => 'Court Clerk',
        'analyst' => 'Judicial Analyst'
    ];
    
    return $greetings[$role] ?? 'User';
}
?>