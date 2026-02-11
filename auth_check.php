<?php

// File: auth_check.php
// Purpose: Authentication & Authorization helper functions ONLY

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* =========================
   AUTHENTICATION
========================= */

/**
 * Check login
 */
function requireLogin() {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header("Location: login.php");
        exit;
    }
}

/* =========================
   AUTHORIZATION (ROLES)
========================= */

/**
 * Require single role
 */
function requireRole(string $role) {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $role) {
        header("Location: access_denied.php");
        exit;
    }
}

/**
 * Require multiple roles
 */
function requireRoles(array $roles) {
    if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], $roles)) {
        header("Location: access_denied.php");
        exit;
    }
}

/* =========================
   ROLE HELPERS
========================= */

function isAdmin()   { return ($_SESSION['user_role'] ?? '') === 'admin'; }
function isJudge()   { return ($_SESSION['user_role'] ?? '') === 'judge'; }
function isLawyer()  { return ($_SESSION['user_role'] ?? '') === 'lawyer'; }
function isClerk()   { return ($_SESSION['user_role'] ?? '') === 'clerk'; }
function isAnalyst() { return ($_SESSION['user_role'] ?? '') === 'analyst'; }

/* =========================
   USER INFO
========================= */

function getUserInfo() {
    return [
        'id'    => $_SESSION['user_id'] ?? null,
        'name'  => $_SESSION['user_name'] ?? 'Guest',
        'email' => $_SESSION['user_email'] ?? null,
        'role'  => $_SESSION['user_role'] ?? 'guest'
    ];
}

/* =========================
   UI HELPERS
========================= */

function getRoleBadge($role = null) {
    $role = $role ?? ($_SESSION['user_role'] ?? 'guest');

    $colors = [
        'admin'   => 'danger',
        'judge'   => 'success',
        'lawyer'  => 'primary',
        'clerk'   => 'warning',
        'analyst' => 'info',
        'guest'   => 'secondary'
    ];

    $color = $colors[$role] ?? 'secondary';
    return "<span class='badge bg-$color'>" . ucfirst($role) . "</span>";
}

/* =========================
   ACTION PERMISSIONS
========================= */

function can($action) {
    $role = $_SESSION['user_role'] ?? 'guest';

    $permissions = [
        'add_case'        => ['admin', 'clerk'],
        'edit_case'       => ['admin', 'clerk'],
        'delete_case'     => ['admin'],
        'add_hearing'     => ['admin', 'judge'],
        'add_judgement'   => ['admin', 'judge'],
        'manage_users'    => ['admin'],
        'export_data'     => ['admin', 'analyst','judge'],
    ];

    return isset($permissions[$action]) && in_array($role, $permissions[$action]);
}

?>