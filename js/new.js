number_of_rows = 0;

const new_row = () =>{
    table = document.getElementById("items");
    table.innerHTML += `<td id="row${number_of_rows}">${number_of_rows + 1}.<input type="text" name="name_of_item" placeholder="Nazwa towaru lub usługi"></td><button id="button${number_of_rows}" onclick="remove_row(${number_of_rows})" type="button">- Usuń wiersz</button>`;
    number_of_rows++;  
}

const remove_row = (row_num) =>{
    row = document.getElementById("row" + row_num);
    button = document.getElementById("button" + row_num);
    button.remove();
    row.remove();
}