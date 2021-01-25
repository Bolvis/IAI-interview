number_of_rows = 0;

const new_row = () => {
    const table = document.getElementById("items");
    table.innerHTML +=
        `<tr id="row${number_of_rows}">
            <td ><input type="text" name="name_of_item" placeholder="Nazwa towaru lub usługi"></td>
            <td><input type="number" placeholder="ilosc"></td>
            <td><input type="text" placeholder="jednostka"></td>
            <td><input type="number" placeholder="cena brutto"></td>
            <td><input type="number" placeholder="Rabat (%)"></td>
            <td>Po rabacie : <div id="after_discount${number_of_rows}"></div></td>
            <td><select>
                    <option value="zw">zw.</option>
                    <option value="0">0%</option>
                    <option value="5">5%</option>
                    <option value="8">8%</option>
                    <option value="23">23%</option>
                </select>
            </td>
        </tr>
        <button id="button${number_of_rows}" onclick="remove_row(${number_of_rows})" type="button">- Usuń wiersz</button>`;
    number_of_rows++;  
}

const remove_row = (row_num) => {
    const row = document.getElementById("row" + row_num);
    const button = document.getElementById("button" + row_num);
    button.remove();
    row.remove();
}
