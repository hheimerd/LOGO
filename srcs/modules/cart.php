<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="cart.css">
</head>
<body>
<?php 
$id = explode(",",$_COOKIE['cart']);
$id = array_map(intval, $id); 
if ($id)
{
	$options = array(
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
			);
	$connection = new PDO('mysql:host=localhost;dbname=films_index;charset=utf8', 'root', 'root', $options);
	$stmt = $connection->query("SELECT film.id, `title`, price FROM film WHERE id in (".implode(',' , $id).") ORDER BY title");
	$product_list = $stmt->fetchAll();
	$connection = null;
}
else
	$product_list = null;
?>
<div id="wrapper">
	<button id="close_button" onclick="window.parent.closeCart()">x</button>
	<form id="table_form">
		<table cellspacing="0">
			<tr><th>✓<th>id<th style="padding: 0 15px;">Имя<th width='100px;'>Цена<th width='30px;'>
		</table>
		<div id="product_list">
			<?php  
			if ($product_list)
			{
				echo "<table cellspacing='0'>";
				foreach ($product_list as $row) {
					echo '<tr><td  width="56px" style="padding: 0;"><input style="width: 22px;" class="checkboxes" name="id[]" type="checkbox" value="'.$row['id'].'" >';
					foreach ($row as $col => $value) {		
						if ($col == 'id')
						{
							$tmp_id = $value;
							echo "<td width='43px'>".$value;
						}
						elseif ($col == 'price')
						{
							echo "<td>".$value.' ₽';
						}
						else
							echo "<td width='134px'>".$value;

					}
					echo "<td width='30px;'><button type='button' onclick='rmCartProduct($tmp_id)' style='color: red; background-color: rgba(0,0,0,0); border: 0; outline: none; font-size:130%;'> X </button>";
				}
				echo "</table>";
			}
			else
				echo "<p style='font-size: 150%; margin-left: 15px;'>Товары отсутствуют</p>";
			?>
		</div>
	</form>
</div>
<script type="text/javascript">
	var wrapper = document.getElementById("wrapper");
	var height = window.getComputedStyle(wrapper, null).height;
	function getCookie(name) 
	{
	  var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
	  return matches ? decodeURIComponent(matches[1]) : '';
	}

	function deleteCookie (cookie_name)
	{
		var cookie_date = new Date ();
		cookie_date.setTime ( cookie_date.getTime() - 1 );
		document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
		location.href=location.href;
	}
	function rmCartProduct (id)
	{
		if (confirm("Удалить товар из корзины?"))
		{
			var res = (getCookie("cart").split(','));
			var index = -1;
			for (var i = res.length - 1; i >= 0; i--) 
			{
				if (parseInt(res[i]) == parseInt(id))
					{
						index = i;
						break;
					}
			}
			if (index < 0)
				return false;
			res.splice(index, 1);
			document.cookie = "cart = " + res.join(',') + ";path=/";
			window.parent.recount();
			location.href=location.href;
		}
	}
</script>
</body>
</html>
