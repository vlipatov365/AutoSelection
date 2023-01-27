var headerInner = document.getElementById('header-inner');
var div = document.createElement('div');
div.setAttribute('style','order: 3; margin: 0 10px');
div.setAttribute('id','header-autoselection-button');
div.innerHTML = '<form action="/local/components/autoselection.php"><input type="submit" value="Автоподбор" class="btn btn-info"></form>';
headerInner.append(div);