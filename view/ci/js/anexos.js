function adicionar() {
        var id = document.getElementById("id").value;
        $("#campos").append("<p id='linha" + id + "'><label for='anexo" + id + "'>Anexo:  </label><input type='file' size='20' name='anexo[]' id='anexo" + id + "'>&nbsp<a href='#' onclick='remover(\"#linha" + id + "\"); return false;'  style='margin-left: 20px;'>Remover</a><p>");
        id = (id - 1) + 2;
        document.getElementById("id").value = id;
}

function remover(id) {
        $(id).remove();
}