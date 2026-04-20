<?php
require_once "../helpers.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../login.php");
    exit;
}

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

if ($email === "" || $password === "") {
    set_flash("error", "Email and password are required.");
    header("Location: ../login.php");
    exit;
}

$stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($user && password_verify($password, $user["password"])) {
    $_SESSION["user_id"] = (int) $user["id"];
    set_flash("success", "Welcome back.");
    header("Location: ../dashboard.php");
    exit;
}

set_flash("error", "Invalid login credentials.");
header("Location: ../login.php");
exit;
?>