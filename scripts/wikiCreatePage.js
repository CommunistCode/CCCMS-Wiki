function addCategoryRow() {

	var table = document.getElementById("categoryTable");
	var rowCount = table.rows.length;
	var row = table.insertRow(rowCount);
  var categoryListObject = document.getElementById("categoryList").cloneNode(true);

	arrayCount = rowCount--;

	var cell1 = row.insertCell(0);
	var cell2 = row.insertCell(1);

	cell1.appendChild(categoryListObject);
	cell2.innerHTML = "<input class='highlightHover' type='button' value='-' onclick=\"delCategoryRow(this.parentNode);\" />";

}
 
function delCategoryRow(tdObject) {

	table = document.getElementById('categoryTable');
	var row = tdObject.parentNode;
	table.deleteRow(row.rowIndex);

}
