<?php
include 'db.php';
session_start();

// Only admin can access
if (!isset($_SESSION['user_email']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

/* ===========================
   COUNT DATA FOR DASHBOARD
=========================== */

// Total number of Students
$totalStudents = $conn->query("SELECT COUNT(*) AS total FROM studentdetail")->fetch_assoc()['total'];

// Total number of Admins
$totalAdmins = $conn->query("SELECT COUNT(*) AS total FROM admin")->fetch_assoc()['total'];

// Total number of Feedback entries
$totalFeedback = $conn->query("SELECT COUNT(*) AS total FROM feedback")->fetch_assoc()['total'];

// Total number of I-Scheme Papers
$totalIscheme_Papers = $conn->query("SELECT COUNT(*) AS total FROM ischeme_papers")->fetch_assoc()['total'];

// Total number of K-Scheme Papers
$totalKscheme_Papers = $conn->query("SELECT COUNT(*) AS total FROM kscheme_papers")->fetch_assoc()['total'];

// Total number of Uploaded Papers (sum of both)
$totalPapers = $totalIscheme_Papers + $totalKscheme_Papers;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #ffffff);
            margin: 0;
            color: #333;
        }
        header {
            background: #0077b6;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }
        header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 600;
        }
        nav {
            margin-top: 8px;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-weight: 500;
            transition: 0.3s;
        }
        nav a:hover {
            color: #90e0ef;
        }
        .container {
            max-width: 1100px;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        h2 { 
            color: #0077b6; 
            margin-bottom: 10px; 
            border-bottom: 2px solid #0077b6; 
            padding-bottom: 5px; 
            text-align: center;
        }
        .stats {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            background: #e9f5ff;
            border: 2px solid #0077b6;
            border-radius: 10px;
            padding: 20px;
            width: 250px;
            text-align: center;
        }
        .card h3 { margin: 0; font-size: 20px; }
        .card p { font-size: 24px; font-weight: bold; color: #0077b6; margin-top: 10px; }
    </style>
</head>
<body>

<header>
    <h1>Admin Dashboard 👨🏻‍💻</h1>
    <nav>
        <a href="Dashboard.php">Dashboard</a>
        <a href="upload_paper.php">Upload Paper</a>
        <a href="Ischeme_papers.php">I-Scheme Paper</a>
        <a href="Kscheme_papers.php">K-Scheme Paper</a>
        <a href="view_student.php">View Students</a>
        <a href="view_feedback.php">Feedback</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="container">
    <h2>📊 Dashboard Overview</h2>

    <div class="stats">    
        <div class="card">
            <h3>Total Students 👨‍🎓</h3>
            <p><?= $totalStudents ?></p>
        </div>

        <div class="card">
            <h3>Total Admins 👤</h3>
            <p><?= $totalAdmins ?></p>
        </div>

        <div class="card">
            <h3>Total Feedback 📬</h3>
            <p><?= $totalFeedback ?></p>
        </div>
    </div>

    <div class="stats">
        <div class="card">
            <h3>Total Papers Uploaded 📤</h3>
            <p><?= $totalPapers ?></p>
        </div>

        <div class="card">
            <h3>Total I-Scheme Papers 📚</h3>
            <p><?= $totalIscheme_Papers ?></p>
        </div>

        <div class="card">
            <h3>Total K-Scheme Papers 📒</h3>
            <p><?= $totalKscheme_Papers ?></p>
        </div>
    </div>

</div>

</body>
</html>
