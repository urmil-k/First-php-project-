<?php
require_once 'includes/session_config.php';

// Get current page filename (e.g., "index.php")
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="pic/favicon.jpg" alt="Logo" style="height: 33px;" class="me-2">
      Apple Store
    </a>

    <!-- Mobile Toggle -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu Items -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">

        <!-- Home -->
        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>" href="index.php">Home</a>
        </li>

        <!-- Products Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= ($current_page == 'category.php') ? 'active' : '' ?>" href="#" id="productsDropdown" role="button" 
             data-bs-toggle="dropdown" aria-expanded="false">
             Products
          </a>
          <ul class="dropdown-menu" aria-labelledby="productsDropdown">
            <li><a class="dropdown-item" href="category.php?category=iphone">iPhone</a></li>
            <li><a class="dropdown-item" href="category.php?category=ipad">iPad</a></li>
            <li><a class="dropdown-item" href="category.php?category=mac">Mac</a></li>
            <li><a class="dropdown-item" href="category.php?category=watch">Watch</a></li>
            <li><a class="dropdown-item" href="category.php?category=others">TV & Others</a></li>
          </ul>
        </li>

        <!-- Cart -->
        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'cart.php') ? 'active' : '' ?>" href="cart.php">
            Cart (<span id="cart-count"><?= isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0 ?></span>)
          </a>
        </li>

        <!-- Account -->
        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'account.php') ? 'active' : '' ?>" href="account.php">Account</a>
        </li>

        <!-- Contact -->
        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'contactus.php') ? 'active' : '' ?>" href="contactus.php">Contact</a>
        </li>

        <!-- Login/Logout -->
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'logout.php') ? 'active' : '' ?>" href="logout.php">Logout</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'login.php') ? 'active' : '' ?>" href="login.php">Login</a>
          </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
