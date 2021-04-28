<?php 
include("./includes/header.php"); 
include("../config/connection.php");
?>
<!-- Data Tables init Script -->
<script>
	$(document).ready( function () {
    $('#table_id').DataTable();
	} );
</script>

<section>
	<header class="main">
		<h1>Credit</h1>
	</header>
	<div class="row gtr-200">
		<div class="col-12 col-12-medium">
			<div class="table-wrapper">
				<!-- <table class="alt"> -->
				<!-- <table id="table_id" class="display"> -->
				<table id="table_id" class="alt">
					<thead>
						<tr>
							<th>Full Name</th>
							<th>Credit</th>
							<th>Manage</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$credit = -2; // exe

							$credit < 0 ? $credit_color = "button" : $credit_color = "green";

							$sql = "SELECT * FROM `client` ORDER BY `id_client` DESC";
							$result = $db->query($sql);
							while($row = $result->fetch(PDO::FETCH_ASSOC)) {

								echo "
									<tr>
										<td><center><a >".$row["full_name"]."</a></center></td>
										<td><center><a href='#' class=".$credit_color.">".$credit." Dhs</a></center></td>
										<td><center><a href='credit_details.php?id=".$row["id_client"]."' class='button primary'>More</a></center></td>
									</tr>
										";
							}
							$db = null;
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>
<!-- Sidebar -->
<?php include("./includes/sidebar.php"); ?>
