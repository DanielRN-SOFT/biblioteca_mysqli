<?php
function validarISBN($isbn) {
    $isbn = str_replace(['-', ' '], '', strtoupper($isbn));

    if (strlen($isbn) == 10) {
        return validarISBN10($isbn);
    } elseif (strlen($isbn) == 13) {
        return validarISBN13($isbn);
    } else {
        return false;
    }
}

 function validarISBN10($isbn) {
    if (strlen($isbn) != 10) return false;

    $suma = 0;
    for ($i = 0; $i < 9; $i++) {
        if (!ctype_digit($isbn[$i])) return false;
        $suma += ($i + 1) * (int)$isbn[$i];
    }

    $ultimo = $isbn[9];
    $ultimo = ($ultimo == 'X') ? 10 : (ctype_digit($ultimo) ? (int)$ultimo : -1);
    if ($ultimo == -1) return false;

    return ($suma % 11) == $ultimo;
}

function validarISBN13($isbn) {
    if (strlen($isbn) != 13 || !ctype_digit($isbn)) return false;

    $suma = 0;
    for ($i = 0; $i < 12; $i++) {
        $num = (int)$isbn[$i];
        $suma += ($i % 2 === 0) ? $num : $num * 3;
    }

    $digitoVerificador = (10 - ($suma % 10)) % 10;
    return $digitoVerificador == (int)$isbn[12];
} 

?>