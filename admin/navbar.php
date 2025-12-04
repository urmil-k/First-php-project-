<?php
// Get current admin page filename
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="adminNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'add_product.php') ? 'active' : '' ?>" href="add_product.php">Add Product</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'manage_user.php') ? 'active' : '' ?>" href="manage_user.php">Manage Users</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'product_list.php') ? 'active' : '' ?>" href="product_list.php">Product List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'order_view.php') ? 'active' : '' ?>" href="order_view.php">Order List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'user_contactus_messages.php') ? 'active' : '' ?>" href="user_contactus_messages.php">Contact Messages</a>
        </li>
      </ul>
      <div class="d-flex">
        <a href="../logout.php" class="btn btn-outline-light">Logout</a>
      </div>
    </div>
  </div>
</nav>
<script src="../script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
