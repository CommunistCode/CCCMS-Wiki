function addDefinitionRow() {

	var table = document.getElementById("defTable");
	var rowCount = table.rows.length;
	var row = table.insertRow(rowCount);

	arrayCount = rowCount--;

	var cell1 = row.insertCell(0);
	var cell2 = row.insertCell(1);
	var cell3 = row.insertCell(2);
	var cell4 = row.insertCell(3);

	cell1.innerHTML = "<input type='text' name='definition["+rowCount+"][heading]' />";
	cell2.innerHTML = "<input type='text' name='definition["+rowCount+"][description]' />";
	cell3.innerHTML = "<input type='text' name='definition["+rowCount+"][dataType]' />";
	cell4.innerHTML = "<input type='button' value='-' onclick=\"delDefinitionRow(this.parentNode);\" />";

}
 
function delDefinitionRow(tdObject) {

	table = document.getElementById('defTable');
	var row = tdObject.parentNode;
	table.deleteRow(row.rowIndex);

}
