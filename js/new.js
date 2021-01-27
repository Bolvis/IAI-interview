let number_of_rows = 0;
const rows = [];

const pushRow = (row_num) => {
    const name_of_item = document.getElementById("name" + row_num);
    const quantity = document.getElementById("quantity" + row_num);
    const unit = document.getElementById("unit" + row_num);
    const price = document.getElementById("price" + row_num);
    const vat = document.getElementById("vat" + row_num);
    rows[row_num] = new Row(name_of_item.value, quantity.value, unit.value, price.value, vat.value);
}

const setRows = () => {
    for (let i = 0; i < rows.length; i++){
        if (document.getElementById("row" + i) != null){
            const name_of_item = document.getElementById("name" + i);
            const quantity = document.getElementById("quantity" + i);
            const unit = document.getElementById("unit" + i);
            const price = document.getElementById("price" + i);
            const vat = document.getElementById("vat" + i);
            name_of_item.value = rows[i].name_of_item;
            quantity.value = rows[i].quantity;
            unit.value = rows[i].unit;
            price.value = rows[i].price_brutto;
            vat.value = rows[i].vat;
        }
    }
}

const new_row = () => {
    const table = document.getElementById("items");
    table.innerHTML +=
        `<tr id="row${number_of_rows}">
            <td><input id="name${number_of_rows}" oninput="pushRow(${number_of_rows})" type="text" name="name_of_item[]" placeholder="Nazwa towaru lub usługi"></td>
            <td><input id="quantity${number_of_rows}" oninput="pushRow(${number_of_rows})" type="number" step=".01" name="quantity[]" placeholder="ilosc"></td>
            <td><input id="unit${number_of_rows}" oninput="pushRow(${number_of_rows})" type="text" name="unit[]" placeholder="jednostka"></td>
            <td><input id="price${number_of_rows}" oninput="pushRow(${number_of_rows})" type="number" step=".01" name="price_brutto[]" placeholder="cena brutto"></td>
            <td><select id="vat${number_of_rows}" onchange="pushRow(${number_of_rows})" name="vat[]">
                    <option value="zw">zw.</option>
                    <option value="0">0%</option>
                    <option value="5">5%</option>
                    <option value="8">8%</option>
                    <option value="23">23%</option>
                </select>
            </td>
            <td style="width: 100px;"><button id="button${number_of_rows}" onclick="remove_row(${number_of_rows})" type="button">- Usuń wiersz</button></td>
        </tr>
        `;
    rows.push(new Row('','', '', '', ''));
    setRows();
    number_of_rows++;  
}

const remove_row = (row_num) => {
    const row = document.getElementById("row" + row_num);
    const button = document.getElementById("button" + row_num);
    button.remove();
    row.remove();
    setRows();
}

