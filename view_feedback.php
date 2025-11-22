<?php 
include 'db.php';

// ✅ Handle search
$search = "";
$whereClause = "";
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = trim($_GET['search']);
    $whereClause = "WHERE name LIKE '%$search%' 
                    OR email LIKE '%$search%' 
                    OR message LIKE '%$search%'";
}

$sql = "SELECT * FROM feedback $whereClause ORDER BY submitted_at DESC";
$result = $conn->query($sql);

if (!$result) {
    die("SQL query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>View Feedback | MSBTE PapersHub</title>
  <style>
/* ===== Base Page Styling ===== */
body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(135deg, #e0f7fa, #ffffff);
  margin: 0;
  color: #333;
}

/* ===== Header ===== */
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

/* ===== Container ===== */
.container {
  max-width: 1100px;
  margin: 40px auto;
  background: #ffffff;
  padding: 30px 40px;
  border-radius: 12px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.container h2 {
  text-align: center;
  color: #0077b6;
  margin-bottom: 20px;
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

/* ===== Table Styling ===== */
table {
  width: 100%;
  border-collapse: collapse;
  font-size: 15px;
  margin-top: 20px;
}

th, td {
  padding: 12px 10px;
  border: 1px solid #ddd;
  text-align: center;
}

th {
  background: #0077b6;
  color: #fff;
  font-weight: 600;
}

tr:nth-child(even) {
  background: #f1fbff;
}

tr:hover {
  background: #d9f2ff;
}

/* ===== Empty Message ===== */
.empty {
  text-align: center;
  color: #777;
  padding: 20px;
}

/* ===== Responsive Table ===== */
@media (max-width: 768px) {
  .container {
    width: 90%;
    padding: 20px;
  }

  table, th, td {
    font-size: 13px;
  }

  nav a {
    margin: 0 8px;
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

<div class="container">
  <h2>📬 User Feedback</h2>

  <!-- ✅ Search Bar -->
  <div class="search-bar">
    <form method="GET" action="">
      <input type="text" name="search" placeholder="Search by name, email, or message..." value="<?= htmlspecialchars($search) ?>">
      <button type="submit">Search</button>
    </form>
  </div>

  <?php if ($result->num_rows > 0): ?>
    <table>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Message</th>
        <th>Submitted At</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td style="text-align:left;"><?= nl2br(htmlspecialchars($row['message'])) ?></td>
          <td><?= $row['submitted_at'] ?></td>
        </tr>
      <?php } ?>
    </table>
  <?php else: ?>
    <p class="empty">
     ❌ No feedback found<?= $search ? " for '<b>" . htmlspecialchars($search) . "</b>'" : "" ?>.
    </p>
  <?php endif; ?>
</div>

</body>
</html>

<?php $conn->close(); ?>
