<!-- Footer Section -->
<footer class="bg-dark text-light pt-4 pb-2 mt-5">
  <div class="container">

    <!-- Logo + Title -->
    <div class="text-center mb-4">
      <a href="index.php" class="d-inline-flex align-items-center text-decoration-none text-light">
        <img src="pic/favicon.jpg" alt="Logo" style="height: 20px;" class="me-2">
        <span class="fw-semibold">Apple Store Online</span>
      </a>
    </div>

    <!-- Footer Links -->
    <div class="row text-start text-sm-center text-md-start small">

      <!-- Shop and Learn -->
      <div class="col-6 col-md-3 mb-3">
        <h6 class="fw-bold">Shop and Learn</h6>
        <ul class="list-unstyled">
            <li><a href="index.php" class="text-light text-decoration-none">Store</a></li>
          <li><a href="category.php?category=mac" class="text-light text-decoration-none">Mac</a></li>
          <li><a href="category.php?category=ipad" class="text-light text-decoration-none">iPad</a></li>
          <li><a href="categery.php?category=iphone" class="text-light text-decoration-none">iPhone</a></li>
          <li><a href="category.php?category=watch" class="text-light text-decoration-none">Watch</a></li>
          <li><a href="category.php?category=others" class="text-light text-decoration-none">TV & Home</a></li>
        </ul>
      </div>

      <!-- Apple Store -->
      <div class="col-6 col-md-3 mb-3">
        <h6 class="fw-bold">Apple Store</h6>
        <ul class="list-unstyled">
          <li><a href="index.php" class="text-light text-decoration-none">Find a Store</a></li>
          <li><a href="#" class="text-light text-decoration-none">Genius Bar</a></li>
          <li><a href="index.php" class="text-light text-decoration-none">Today at Apple</a></li>
          <li><a href="index.php" class="text-light text-decoration-none">Apple Summer Camp</a></li>
          <li><a href="#" class="text-light text-decoration-none">Ways to Buy</a></li>
          <li><a href="about.php" class="text-light text-decoration-none">Apple Trade In</a></li>
          <li><a href="about.php" class="text-light text-decoration-none">Recycling Programme</a></li>
          <li><a href="account.php" class="text-light text-decoration-none">Order Status</a></li>
        </ul>
      </div>

      <!-- About Apple -->
      <div class="col-6 col-md-3 mb-3">
        <h6 class="fw-bold">About Apple</h6>
        <ul class="list-unstyled">
          <li><a href="about.php" class="text-light text-decoration-none">Newsroom</a></li>
          <li><a href="about.php" class="text-light text-decoration-none">Apple Leadership</a></li>
          <li><a href="about.php" class="text-light text-decoration-none">Terms & Conditions</a></li>
          <li><a href="about.php" class="text-light text-decoration-none">Careers</a></li>
          <li><a href="about.php" class="text-light text-decoration-none">Contact Apple</a></li>
        </ul>
      </div>

      <!-- Account -->
      <div class="col-6 col-md-3 mb-3">
        <h6 class="fw-bold">Account</h6>
        <ul class="list-unstyled">
          <li><a href="#" class="text-light text-decoration-none">Manage Your Apple Account</a></li>
          <li><a href="account.php" class="text-light text-decoration-none">Apple Store Account</a></li>
          <li><a href="#" class="text-light text-decoration-none">iCloud.com</a></li>
        </ul>
      </div>
    </div>

    <!-- Footer Bottom -->
    <div class="text-center border-top border-secondary pt-3 mt-3 small text-light">
      <p class="mb-0">© <?= date('Y'); ?> Apple Store. All rights reserved.</p>
    </div>

  </div>
</footer>

<style>
  footer h6 {
    font-size: 0.9rem;
    margin-bottom: 0.8rem;
  }
  footer ul li {
    margin-bottom: 0.4rem;
  }
  footer a:hover {
    color: #fff;
  }
</style>
