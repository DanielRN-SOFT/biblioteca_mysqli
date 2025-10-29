<?php
function validarISBN($isbn) {
    $isbn = str_replace(['-', ' '], '', $isbn);
    return (ctype_digit($isbn) && (strlen($isbn) == 10 || strlen($isbn) == 13));
}
?>