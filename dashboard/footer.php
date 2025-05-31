<?php
require_once __DIR__ . '/../z_db.php';

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    print "
				<script language='javascript'>
					window.location = 'login.php';
				</script>
			";

}?>
<footer class="footer">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <p>
          All rights are reserved © BaranBoya.
        </p>
      </div>
      <div class="col-sm-6">
        <div class="text-sm-end d-none d-sm-block">
          <a href="https://github.com/devmustafaulutas">Design & Develop by Mustafa Ulutaş.</a>
        </div>
      </div>
    </div>
  </div>
</footer>
</div>
</div>

<button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
  <i class="ri-arrow-up-line"></i>
</button>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<!-- datatables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<!-- apexcharts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>