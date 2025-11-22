<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MSBTE PapersHub | Question Paper Management System</title>
 
<body>
  <header>
    <h1>📚 MSBTE PapersHub </h1>
    <nav>
      <a href="main_Page.php">Home</a>
    </nav>
  </header>
 
  <style>

    * ===== Global Reset & Base ===== */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #f9fafb;
  color: #1e293b;
  line-height: 1.6;
}

/* ===== Header ===== */
header {
  background: #2577ceff;
  padding: 10px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 2px solid #00a86b;
  position: sticky;
  top: 0;
  z-index: 1000;
}

header h1 {
  color: #ffffffff;
  font-size: 1.8rem;
  font-weight: 700;
}

nav a {
  margin: 0 12px;
  text-decoration: none;
  color: #1e293b;
  font-weight: 500;
  transition: color 0.3s;
}

nav a:hover {
  color: #00a86b;
}

/* ===== Contact Section ===== */
.contact {
  margin: 60px auto;
  padding: 40px;
  max-width: 800px;
  text-align: center;
  background: #fff;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}

.contact h2 {
  font-size: 24px;
  margin-bottom: 18px;
  color: #0284c7;
  font-weight: 700;
}

.contact form {
  display: flex;
  flex-direction: column;
  gap: 14px;
  margin-top: 20px;
}

.contact input, 
.contact textarea {
  padding: 12px;
  border-radius: 8px;
  border: 1px solid #cbd5e1;
  background: #f8fafc;
  color: #1e293b;
  font-size: 15px;
}

.contact input:focus, 
.contact textarea:focus {
  outline: none;
  border-color: #0284c7;
  box-shadow: 0 0 6px rgba(2,132,199,0.3);
}

.contact button {
  background: linear-gradient(135deg, #0284c7, #00a86b);
  color: #fff;
  border: none;
  padding: 12px 20px;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s;
}

.contact button:hover {
  background: linear-gradient(135deg, #026aa7, #028059);
}

/* ===== Footer ===== */
footer {
  background: #111827;
  color: #d1d5db;
  padding: 25px;
  text-align: center;
}

footer h3 {
  margin-bottom: 10px;
  color: #f9fafb;
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
}

   </style> 
 <!-- ===== Contact Section ===== -->
<section id="contact" class="contact">
  <h2>Contact Us</h2>
  <p style="text-align:center;">Have questions, suggestions, or issues? Feel free to reach out:</p>
  <form method="POST" action="contact_submit.php" style="margin-top:20px; max-width:500px; margin:auto;">
    <input type="text" name="name" placeholder="Your Name" required>
    <input type="email" name="email" placeholder="Your Email" required>
    <textarea name="message" placeholder="Your Message" rows="4" required></textarea>
    <button type="submit">📩 Send Message</button>
  </form>
</section>

<footer>
  <div>
      <h3>Contact Info</h3>
      <p>📍 Yavatmal, India</p>
      <p>📞 +91 91562 40822</p>
      <p>📧 support@msbtepaperhub.com</p>
  </div> 
  <div>
      <p>© <?php echo date("Y"); ?> MSBTE PapersHub | Developed by Yatharth Rahangdale</p>
  </div>
</footer>