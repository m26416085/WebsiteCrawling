<?php
 
$dataPoints = array(
	array("y" => 180000, "label" => "Sunday  asdas "),
	array("y" => 15, "label" => "Monday"),
	array("y" => 25, "label" => "Tuesday"),
	array("y" => 5, "label" => "Wednesday"),
    array("y" => 10000, "label" => "Thursday"),
	array("y" => 0, "label" => "Friday"),
    array("y" => 20, "label" => "Saturday"),
    array("y" => 20, "label" => "sunday")
);
echo "<pre>";
    print_r($dataPoints);
    echo "</pre>";
?>
<!DOCTYPE HTML>
<html>
<head>
<script>
 function  a() {
 
var chart = new CanvasJS.Chart("chartContainer", {
	title: {
		text: "Push-ups Over a Week"
	},
	axisY: {
		title: "Number of Push-ups"
	},
	data: [{
		type: "line",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>
</head>
<body>
<button onclick="a()">create grafik</button>
<div id="chartContainer"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>        