<?php  
include 'db.php';
session_start();

// Optional admin-only restriction
/*
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}
*/

// ✅ Handle delete request
if (isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $delete_query = "DELETE FROM studentdetail WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_query)) {
        $msg = "✅ Student record deleted successfully.";
    } else {
        $msg = "❌ Error deleting record: " . mysqli_error($conn);
    }
}

// ✅ Handle search
$search = "";
$whereClause = "";
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = trim($_GET['search']);
    $whereClause = "WHERE Name LIKE '%$search%' 
                    OR Email LIKE '%$search%' 
                    OR Department LIKE '%$search%' 
                    OR Year LIKE '%$search%' 
                    OR College LIKE '%$search%'";
}

$query = "SELECT id, Name, Email, Department, Year, College FROM studentdetail $whereClause ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Registered Students | MSBTE Paper Hub</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <style>
    :root {
      --primary: #0077b6;
      --primary-light: #90e0ef;
      --bg-light: #f1fbff;
      --danger: #dc3545;
      --danger-dark: #b71c1c;
      --text-dark: #333;
      --card-bg: #fff;
      --font: 'Poppins', sans-serif;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #e0f7fa, #ffffff);
      margin: 0;
      color: #333;
    }

    header {
      background: var(--primary);
      color: #fff;
      text-align: center;
      padding: 20px 10px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.15);
    }

    header h1 {
      margin-bottom: 8px;
      font-size: 26px;
      font-weight: 600;
    }

    nav {
      margin-top: 5px;
    }

    nav a {
      color: #fff;
      text-decoration: none;
      margin: 0 12px;
      font-weight: 500;
      padding: 6px 10px;
      border-radius: 6px;
      transition: 0.3s ease;
    }

    nav a:hover {
      background: var(--primary-light);
      color: var(--primary);
    }

    .container {
      max-width: 1100px;
      margin: 40px auto;
      background: var(--card-bg);
      padding: 35px 40px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }

    .container h2 {
      text-align: center;
      color: var(--primary);
      font-size: 24px;
      margin-bottom: 20px;
    }

    .msg {
      text-align: center;
      font-weight: 600;
      color: var(--primary);
      background: #e0f7fa;
      padding: 10px;
      border-radius: 8px;
      margin: 15px auto;
      width: 60%;
    }

    /* ===== Search Bar ===== */
    .search-bar {
      text-align: center;
      margin-bottom: 25px;
    }

    .search-bar input[type="text"] {
      padding: 10px 15px;
      width: 60%;
      max-width: 400px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
      outline: none;
    }

    .search-bar button {
      padding: 10px 20px;
      background: #0077b6;
      color: #fff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 500;
      transition: 0.3s;
    }

    .search-bar button:hover {
      background: #023e8a;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 15px;
      margin-top: 20px;
      border-radius: 10px;
      overflow: hidden;
    }

    th, td {
      padding: 12px 10px;
      border: 1px solid #ddd;
      text-align: center;
    }

    th {
      background: var(--primary);
      color: #fff;
      font-weight: 600;
    }

    tr:nth-child(even) {
      background: var(--bg-light);
    }

    tr:hover {
      background: #d9f2ff;
    }

    .delete-btn {
      background-color: var(--danger);
      color: #fff;
      border: none;
      padding: 6px 12px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      transition: 0.3s ease;
    }

    .delete-btn:hover {
      background-color: var(--danger-dark);
    }

    a {
      color: #0077b6;
      text-decoration: none;
      font-weight: 500;
    }

    a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .container {
        width: 92%;
        padding: 20px;
      }

      table, th, td {
        font-size: 13px;
      }

      nav a {
        display: inline-block;
        margin: 4px 6px;
      }

      .search-bar input[type="text"] {
        width: 80%;
      }
    }
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

<?php if (isset($msg)): ?>
  <p class="msg"><?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

<div class="container">
  <h2>👨‍🎓 Registered Students</h2>

  <!-- ✅ Search Bar -->
  <div class="search-bar">
    <form method="GET" action="">
      <input type="text" name="search" placeholder="Search by name, email, department, year, or college..." value="<?= htmlspecialchars($search) ?>">
      <button type="submit">Search</button>
    </form>
  </div>

  <?php if ($result && mysqli_num_rows($result) > 0): ?>
    <table>
      <thead>
        <tr>
          <th>#ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Department</th>
          <th>Year</th>
          <th>College</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= htmlspecialchars($row['id']) ?></td>
          <td><?= htmlspecialchars($row['Name']) ?></td>
          <td><?= htmlspecialchars($row['Email']) ?></td>
          <td><?= htmlspecialchars($row['Department']) ?></td>
          <td><?= htmlspecialchars($row['Year']) ?></td>
          <td><?= htmlspecialchars($row['College']) ?></td>
          <td>
            <form method="POST" onsubmit="return confirm('Are you sure you want to remove this student?');">
              <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
              <button type="submit" class="delete-btn">Remove</button>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p style="text-align:center; color:red; margin-top:20px;">
      ❌ No students found<?= $search ? " for '<b>" . htmlspecialchars($search) . "</b>'" : "" ?>.
    </p>
  <?php endif; ?>
</div>

<?php $conn->close(); ?>
</body>
</html>
