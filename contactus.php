<?php
require_once 'includes/session_config.php';
require_once 'includes/dbc.inc.php';

// Handle form submission
$successMsg = $errorMsg = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = $_POST["name"] ?? "";
    $uid     = $_POST["uid"] ?? null;
    $email   = $_POST["email"] ?? "";
    $subject = $_POST["subject"] ?? "";
    $message = $_POST["message"] ?? "";

    if ($name && $email && $subject && $message) {
        $stmt = $pdo->prepare("INSERT INTO contactus (name, email, subject, message, created_at, uid) 
                               VALUES (:name, :email, :subject, :message, NOW(), :uid)");
        $stmt->execute([
            ':name'    => $name,
            ':email'   => $email,
            ':subject' => $subject,
            ':message' => $message,
            ':uid'     => $uid
        ]);

        $successMsg = "✅ Thank you, $name. We received your message.";
    } else {
        $errorMsg = "❌ Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Contact Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <!-- Page Title -->
        <div class="text-center mb-5">
            <h2>📩 Get in Touch with Us</h2>
            <p class="text-muted">We’d love to hear from you! Whether you have a question, feedback, or need support, our team is ready to help.</p>
        </div>

        <div class="row g-4">
            <!-- Contact Info -->
            <div class="col-md-4">
                <div class="card shadow-sm p-4">
                    <h5>🏢 Our Office</h5>
                    <p>215 Tulsi Arcade<br>Mota varachha<br>Surat, India</p>
                    <h5>📞 Call Us</h5>
                    <p>+91 98765 43210</p>
                    <h5>📧 Email</h5>
                    <p>support@example.com</p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-md-8">
                <?php if ($successMsg): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($successMsg) ?></div>
                <?php elseif ($errorMsg): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
                <?php endif; ?>

                <form method="POST" action="contactus.php" class="card p-4 shadow-sm">
                    <h4 class="mb-3">📨 Send Us a Message</h4>

                    <div class="mb-3">
                        <input type="hidden" name="uid" value="<?= htmlspecialchars($_SESSION['user_id'] ?? '') ?>">
                        <label for="name" class="form-label">👤 Your Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">📧 Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">📝 Subject</label>
                        <input type="text" name="subject" id="subject" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">💬 Message</label>
                        <textarea name="message" id="message" rows="4" class="form-control" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">📨 Send Message</button>
                </form>
            </div>
        </div>

        <!-- Map -->
        <div class="mt-5 mb-5">
            <h4 class="mb-3">📍 Find Us</h4>
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d378.0996162331991!2d72.88041142194847!3d21.23895837410916!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be04f705dffe915%3A0x1fc83e2ebcf890f5!2sTulsi%20Arcade!5e0!3m2!1sen!2sin!4v1756725471280!5m2!1sen!2sin"
                width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>

</html>