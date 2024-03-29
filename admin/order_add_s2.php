<?php 
include("./includes/header.php"); 
include("../config/connection.php");
?>

<?php
	if(isset($_POST['append']) && isset($_POST["menu"])){
		isset($_POST["id_client"]) ? $id_client = htmlspecialchars($_POST["id_client"]) : "";
		isset($_POST["full_name"]) ? $full_name = htmlspecialchars($_POST["full_name"]) : "";
		isset($_POST["tele"]) ? $tele = htmlspecialchars($_POST["tele"]) : "";
		isset($_POST["class"]) ? $class = htmlspecialchars($_POST["class"]) : "";
		isset($_POST["status"]) ? $status = htmlspecialchars($_POST["status"]) : "";
		isset($_POST["remarque"]) ? $remarque = htmlspecialchars($_POST["remarque"]) : "";
		isset($_POST["remise"]) ? $remise = htmlspecialchars($_POST["remise"]) : "";
		
		$menus = $_POST["menu"];
		$prices = $_POST["price"];
		$qtes = $_POST["qte"];
		$order_menus = "";
		$price_total= 0;
		foreach( $menus as $i => $menu ) {
			if(!is_numeric($qtes[$i])) $qtes[$i] = 1;

			$order_menus .= $menu." - ".$prices[$i]."Dhs - Qte:".$qtes[$i]."\r\n";
			$price_total += ($prices[$i] * $qtes[$i]);
		}
		$price_remise = $remise * $price_total / 100;
		$price_final = $price_total - $price_remise;

	// Start Generate Id Day ##############################################################
		// Get Unix timestamp Days since (January 1 1970 00:00:00 GMT).
		$x = time();
		$timestamp_day = intval($x/86400);

		// Get the last record to get `id_day` and `timestamp_day`
		$query = 'SELECT * FROM `orders` ORDER BY `id_order` DESC LIMIT 1';
		$query = $db->prepare($query);
		$query->execute();
		$count = $query->rowCount();
		$la_case = $query->fetchAll(\PDO::FETCH_ASSOC);
		if ($count > 0) {
			$timestamp_day_db=$la_case[0]['timestamp_day'];
			$id_day_db=$la_case[0]['id_day'];
		} else {
			$timestamp_day_db = $timestamp_day;
			$id_day_db = 0;
		}

		if ($timestamp_day == $timestamp_day_db) {
			$id_day = $id_day_db + 1;
		} else {
			$id_day = 1;
		}
		
	// End Generate Id Day ##############################################################


	$query =	'INSERT INTO `orders`(`id_client`, `full_name`, `tele`, `class`, `status`, `remarque`, `remise`, `order_menus`, `price_total`,
						`price_remise`, `price_final`,`timestamp_day`,`id_day`) 
						VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)';
		$query = $db->prepare($query);

		// print_r([$id_client, $full_name, $tele, $class, $status, $remarque, $remise, $order_menus, $price_total, $price_remise, $price_final]);

		if ($query->execute([$id_client, $full_name, $tele, $class, $status, $remarque, $remise, $order_menus, $price_total, $price_remise, $price_final, $timestamp_day, $id_day])) {
			echo "
				<script>
					const msg = 'Done.';
					window.location.href='order_list.php?msg='+msg;
				</script>
				";
		} else {
			echo "
				<script>
					const msg = 'Sorry, something went wrong!';
					window.location.href='order_add_s1.php.php?msg='+msg;
				</script>
				";
		}
	} else if(isset($_POST['append'])){
		echo "
			<script>
				const msg = 'Sorry, something went wrong!';
				window.location.href='order_add_s1.php?msg='+msg;
			</script>
			";
	}
?>

<!-- Data Tables init Script -->
<script>
	$(document).ready( function () {
    $('#table_id').DataTable();
	} );
</script>

<section>
	<header class="main">
		<h1>Choose Menus</h1>
	</header>
	<div class="row gtr-200">
		<div class="col-12 col-12-medium">
			<div class="table-wrapper">
				<form method="POST" action="order_add_s2.php">
					<?php
						// recover Client Data
						isset($_GET["id_client"]) ? $id_client = htmlspecialchars($_GET["id_client"]) : "";
						isset($_GET["full_name"]) ? $full_name = htmlspecialchars($_GET["full_name"]) : "";
						isset($_GET["tele"]) ? $tele = htmlspecialchars($_GET["tele"]) : "";
						isset($_GET["class"]) ? $class = htmlspecialchars($_GET["class"]) : "";
					
					?>
					<input type="hidden" name="id_client" value="<?= $id_client ?>" />
					<input type="hidden" name="full_name" value="<?= $full_name ?>" />
					<input type="hidden" name="tele" value="<?= $tele ?>" />
					<input type="hidden" name="class" value="<?= $class ?>" />
					<input type="hidden" name="status" value="Pending" />

					<!-- <table class="alt"> -->
					<table id="table_id" class="alt">
						<thead>
							<tr>
								<th>Name</th>
								<th>Price</th>
								<th>Qte</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$query = "SELECT * FROM `menu`";
								$query = $db->query($query);
								$query->execute();
								$count = $query->rowCount();
								$row = $query->fetchAll(PDO::FETCH_ASSOC);
								$i = 0;
								while($i < $count) {
									echo "
											<tr>
												<td>
													<center>
														<input type='checkbox' id=".$row[$i]["id_menu"]." name='menu[]' value=".$row[$i]["menu_name"].">
														<label for=".$row[$i]["id_menu"].">".$row[$i]["menu_name"]."</label>
													</center>
												</td>
												<td><center>".$row[$i]["menu_price"]." Dhs</center></td>
												<input type='hidden' value=".$row[$i]["menu_price"]." name='price[]' />
												<td>
													<center>
														<div class='col-3 col-12-xsmall'>
															<input type='number' name='qte[]' />
														</div>
													</center>
												</td>
											</tr>
											";
										$i++;
								}
							?>
						</tbody>
					</table>
							</br>

					<div class="row gtr-uniform">
						<div class="col-6 col-12-xsmall">
							<select name="remise" id="demo-group">
								<option selected value="0">Remise</option>
								<option value="25">25%</option>
								<option value="50">50%</option>
								<option value="100">100%</option>
							</select>
						</div>
						<div class="col-6 col-12-xsmall">
							<input type="text" name="remarque" placeholder="Remarque" />
						</div>
						<div class="col-12 col-12-xsmall">
							<input type="submit" name="append" value="append" class="button primary" />
						</div>
					</div>
				</form>



			</div>
		</div>
	</div>
</section>
<!-- Sidebar -->
<?php include("./includes/sidebar.php"); ?>
