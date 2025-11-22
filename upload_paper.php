<?php
include 'db.php';
session_start();

// Only admin can access
if (!isset($_SESSION['user_email']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$mes = "";

// Handle Upload
if (isset($_POST['upload'])) {
    $branch   = trim($_POST['branch']);
    $semester = trim($_POST['semester']);
    $year     = trim($_POST['year']);
    $examType = trim($_POST['exam_type']);

    if (empty($branch) || empty($semester) || empty($year) || empty($examType) || !isset($_FILES['paperFiles'])) {
        $mes = "❌ All fields are required.";
    } else {
        $files = $_FILES['paperFiles'];
        $total = count($files['name']);
        $successCount = 0;
        $failCount = 0;

        for ($i = 0; $i < $total; $i++) {
            $fileNameInput = trim($_POST['subjects'][$i] ?? '');
            if (empty($fileNameInput)) {
                $failCount++;
                continue;
            }

            if ($files['type'][$i] != "application/pdf") {
                $failCount++;
                continue;
            }

            // Folder structure
            $uploadDir = "papers/" . $examType . "/" . preg_replace('/\s+/', '_', $branch) .
                         "/Semester_" . $semester . "/Year_" . $year . "/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $fileName = strtolower(str_replace(' ', '_', $fileNameInput)) . "_" . $year . ".pdf";
            $uploadFile = $uploadDir . $fileName;

            if (move_uploaded_file($files['tmp_name'][$i], $uploadFile)) {
                $table = ($examType === "University") ? "ischeme_papers" : "kscheme_papers";

                $stmt = $conn->prepare("INSERT INTO $table (branch, semester, year, subject, filename, uploaded_by) VALUES (?, ?, ?, ?, ?, ?)");
                if (!$stmt) die("SQL Error: " . $conn->error);
                $stmt->bind_param("sissss", $branch, $semester, $year, $fileNameInput, $fileName, $_SESSION['user_email']);
                $stmt->execute();
                $stmt->close();
                $successCount++;
            } else {
                $failCount++;
            }
        }

        $mes = "✅ Uploaded $successCount papers. Failed: $failCount.";
    }

    echo "<script>alert('" . addslashes($mes) . "'); window.location='upload_paper.php';</script>";
}

// Fetch papers
$ischeme_papers = $conn->query("SELECT * FROM ischeme_papers ORDER BY uploaded_on DESC");
$kscheme_papers = $conn->query("SELECT * FROM kscheme_papers ORDER BY uploaded_on DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Upload Papers</title>
<style>
/* ===== General Page Styling ===== */
body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(135deg, #dfe9f3, #ffffff);
  margin: 0;
  color: #333;
}

/* ===== Header Navigation ===== */
header {
  background: #0077b6;
  color: white;
  padding: 18px 0;
  text-align: center;
  box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}

header h1 {
  margin: 0;
  font-size: 26px;
  font-weight: 600;
}

nav {
  margin-top: 10px;
}

nav a {
  color: white;
  text-decoration: none;
  margin: 0 12px;
  font-weight: 500;
  transition: 0.3s;
}

nav a:hover {
  color: #90e0ef;
}

/* ===== Form Container ===== */
.container {
  background: white;
  max-width: 850px;
  margin: 40px auto;
  padding: 30px 40px;
  border-radius: 12px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.container h1, .container h2 {
  text-align: center;
  color: #0077b6;
  margin-bottom: 25px;
}

/* ===== Form Inputs ===== */
form input, form select, form button {
  width: 100%;
  padding: 12px;
  margin-bottom: 15px;
  font-size: 15px;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-sizing: border-box;
}

form input:focus, form select:focus {
  outline: none;
  border-color: #0077b6;
  box-shadow: 0 0 4px rgba(0, 119, 182, 0.3);
}

/* ===== Buttons ===== */
button {
  background-color: #0077b6;
  color: white;
  font-size: 16px;
  font-weight: 600;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.3s;
}

button:hover { background-color: #023e8a; }

#addMore {
  background-color: #48cae4;
}

#addMore:hover {
  background-color: #0096c7;
}

/* ===== Table ===== */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  font-size: 14px;
}

th, td {
  padding: 12px 10px;
  border: 1px solid #ddd;
  text-align: center;
}

th {
  background: #0077b6;
  color: white;
}

tr:nth-child(even) {
  background: #f5faff;
}

tr:hover {
  background: #dff6ff;
}

/* ===== Links ===== */
a {
  color: #0077b6;
  text-decoration: none;
  font-weight: 500;
}

a:hover {
  text-decoration: underline;
}

/* ===== Responsive ===== */
@media (max-width: 768px) {
  .container {
    width: 90%;
    padding: 20px;
  }

  table {
    font-size: 12px;
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
  <h1>📤 Upload Question Papers</h1>
  <form method="post" enctype="multipart/form-data">
    <!-- ✅ Dropdown for Branch -->
<select name="branch" id="branch" required>
  <option value="">-- Select Branch --</option>
  <option value="CSE">Computer Engineering</option>
  <option value="IT">Information Technology</option>
  <option value="MECH">Mechanical Engineering</option>
  <option value="CIVIL">Civil Engineering</option>
  <option value="EE">Electrical Engineering</option>
  <option value="ENTC">Electronics & Telecommunication</option>
  <option value="TEXT">Textile Engineering</option>
  <option value="CHEM">Chemical Engineering</option>
</select>

<!-- ✅ Semester -->
<select name="semester" id="semester" required>
  <option value="">-- Select Semester --</option>
  <?php for ($i = 1; $i <= 6; $i++): ?>
    <option value="<?= $i ?>">Semester <?= $i ?></option>
  <?php endfor; ?>
</select>

<input type="text" name="year" placeholder="Year (e.g. S-2025 or W-2025)" required>

<!-- ✅ Scheme Type -->
<select name="exam_type" id="exam_type" required>
  <option value="">-- Select Scheme --</option>
  <option value="I-Scheme">I-Scheme</option>
  <option value="K-Scheme">K-Scheme</option>
</select>

<!-- ✅ Subjects + Files -->
<div id="fileInputs">
  <div class="file-group">
    <select name="subjects[]" class="subject-dropdown" required>
      <option value="">-- Select Subject --</option>
    </select>
    <input type="file" name="paperFiles[]" accept="application/pdf" required>
  </div>
</div>

<button type="button" id="addMore">➕ Add More Subjects</button>
<button type="submit" name="upload">📎 Upload Papers</button>

<script>
/* ==========================================================
   SUBJECT MAPPING by Scheme → Branch → Semester
   ========================================================== */
const subjectData = {
  "I-Scheme": {
    "CSE": {
      1: ["Basic Programming", "Applied Physics", "Maths-I", "Communication Skills"],
      2: ["C Programming", "Digital Logic", "Maths-II", "Engineering Graphics"],
      3: ["Data Structures", "Database Management", "OOP with Java", "Computer Networks"],
      4: ["Operating Systems", "Software Engineering", "Microprocessor", "Web Technology"],
      5: ["Internet of Things", "Machine Learning", "Cloud Computing", "Cyber Security"],
      6: ["Artificial Intelligence", "Big Data", "Mobile App Development", "Project"]
    },
    "IT": {
      3: ["Data Structures", "Database Management", "OOP", "Web Development"],
      4: ["Operating Systems", "Software Engineering", "Computer Networks"],
      5: ["IOT", "Cloud Computing", "Data Analytics"],
      6: ["Machine Learning", "Cyber Security", "Blockchain", "Mini Project"]
    },
    "MECH": {
      3: ["Thermodynamics", "Strength of Materials", "Fluid Mechanics", "Engineering Metallurgy"],
      4: ["Theory of Machines", "Heat Transfer", "Manufacturing Process"],
      5: ["Machine Design", "Refrigeration & Air Conditioning", "Industrial Engineering"],
      6: ["Robotics", "Power Plant", "Project"]
    }
    // ...add other branches
  },
  "K-Scheme": {
    "CSE": {
      1: ["Programming Fundamentals", "Applied Physics", "Mathematics-I", "Engineering Chemistry"],
      2: ["C Programming", "Digital Electronics", "Mathematics-II", "Professional Communication"],
      3: ["Data Structures", "OOP using C++", "Discrete Maths", "Operating Systems"],
      4: ["DBMS", "Computer Networks", "Software Testing", "Microcontrollers"],
      5: ["AI", "Web Technologies", "Cloud Computing", "Compiler Design"],
      6: ["Data Science", "Cyber Security", "Project Work"]
    },
    "IT": {
      3: ["Data Structures", "OOP", "Web Design", "Operating Systems"],
      4: ["DBMS", "Networks", "Software Engineering"],
      5: ["ML", "Cloud", "IoT"],
      6: ["AI", "Cyber Security", "Mini Project"]
    },
    "MECH": {
      3: ["Applied Thermodynamics", "Strength of Materials", "Fluid Mechanics"],
      4: ["Theory of Machines", "Manufacturing Technology", "Heat Transfer"],
      5: ["Design of Machine Elements", "Industrial Management", "CAD/CAM"],
      6: ["Mechatronics", "Automation", "Major Project"]
    }
    // ...add other branches
  }
};

/* ==========================================================
   UPDATE SUBJECT DROPDOWNS
   ========================================================== */
function updateSubjects() {
  const scheme = document.getElementById("exam_type").value;
  const branch = document.getElementById("branch").value;
  const sem = document.getElementById("semester").value;
  const allDropdowns = document.querySelectorAll(".subject-dropdown");

  allDropdowns.forEach(dd => {
    dd.innerHTML = '<option value="">-- Select Subject --</option>';
    if (subjectData[scheme] && subjectData[scheme][branch] && subjectData[scheme][branch][sem]) {
      subjectData[scheme][branch][sem].forEach(sub => {
        const opt = document.createElement("option");
        opt.value = sub;
        opt.textContent = sub;
        dd.appendChild(opt);
      });
    }
  });
}

/* ==========================================================
   EVENT LISTENERS
   ========================================================== */
document.getElementById("exam_type").addEventListener("change", updateSubjects);
document.getElementById("branch").addEventListener("change", updateSubjects);
document.getElementById("semester").addEventListener("change", updateSubjects);

/* ==========================================================
   ADD MORE SUBJECTS
   ========================================================== */
document.getElementById("addMore").addEventListener("click", function() {
  const container = document.getElementById("fileInputs");
  const div = document.createElement("div");
  div.classList.add("file-group");
  div.innerHTML = `
    <select name="subjects[]" class="subject-dropdown" required>
      <option value="">-- Select Subject --</option>
    </select>
    <input type="file" name="paperFiles[]" accept="application/pdf" required>
  `;
  container.appendChild(div);
  updateSubjects(); // populate new dropdowns
});
</script>

</body>
</html>
