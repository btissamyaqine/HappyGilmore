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
		<h1>Ingredients</h1>
		<a href="ingredient_add.php" type="submit" class="button primary">Add New Ingredient</a>
	</header>
	<div class="row gtr-200">
		<div class="col-12 col-12-medium">
			<div class="table-wrapper">
				<table id="table_id" class="display">
					<thead>
						<tr>
							<th>Ingredient Name</th>
							<th>Create At</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php
							$query = "SELECT * FROM `ingredient`";
							$query = $db->query($query);
							$query->execute();
							$count = $query->rowCount();
							$row = $query->fetchAll(PDO::FETCH_ASSOC);
							$i = 0;
							while($i < $count) {
								echo "
									<tr>
										<td><center>".$row[$i]["name_ing"]."</center></td>
										<td><center>".$row[$i]["create_at"]."</center></td>
										<td><center><a href='ingredient_delete.php?id=".$row[$i]["id_ing"]."' onClick='return myConfirm();' class='button'>X</a></center></td>
									</tr>
										";
									$i++;
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>
<!-- Sidebar -->

<script>
function myConfirm() {
  var result = confirm("Want to delete?");
  if (result==true) {
   return true;
  } else {
   return false;
  }
}
</script>

<?php include("./includes/sidebar.php"); ?>

