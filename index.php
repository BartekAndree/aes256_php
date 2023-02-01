<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Szyfrowanie i odszyfrowanie</title>
    <link icon rel="icon" href="https://cdn-icons-png.flaticon.com/512/1792/1792193.png">
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 60%;
        margin: 5vh auto;
    }

    form {
        margin: 20px 0;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    label {
        font-weight: bold;
        margin-right: 10px;
    }

    input[type="text"] {
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    textarea {
        width: 100%;
        margin-bottom: 20px;
        padding: 6px 10px;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    .result {
        margin: 20px 0;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
</style>

<body>
    <?php
    //inicjalizacja sesji
    session_start();

    //sprawdzenie czy został wysłany formularz szyfrowania
    if (isset($_POST["submitEnc"])) {
        //pobranie plaintextu i klucza szyfrującego z formularza
        $plaintext = $_POST["plaintext"];
        $password = $_POST["secretkey"];
        //ustawienie metody szyfrowania
        $method = "aes-256-cbc";

        //generowanie losowego wektora inicjującego
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
        //zapisanie wektora inicjującego do sesji
        $_SESSION['iv'] = $iv;
        //szyfrowanie tekstu
        $ciphertext = openssl_encrypt($plaintext, $method, $password, 0, $iv);

        $operation = 'encrypt';
        $result = $ciphertext;
    }

    //sprawdzenie czy został wysłany formularz deszyfrowania
    if (isset($_POST["submitDec"])) {
        //pobranie ciphertextu i klucza szyfrującego z formularza
        $ciphertext = $_POST["ciphertext"];
        $password = $_POST["secretkey"];
        //ustawienie metody szyfrowania
        $method = "aes-256-cbc";
        //pobranie wektora inicjującego z sesji
        $iv = $_SESSION['iv'];

        //deszyfrowanie tekstu
        $original_plaintext = openssl_decrypt($ciphertext, $method, $password, 0, $iv);

        $operation = 'decrypt';
        $result = $original_plaintext;
    }
    ?>

    <div class="container">
        <form method="post">
            <label for="plaintext">Wpisz tekst do zaszyfrowania:</label>
            <textarea name="plaintext" id="plaintext" rows="4" cols="50"></textarea>
            <br>
            <label for="secretkey">Wpisz klucz szyfrujący:</label>
            <input type="text" name="secretkey" id="secretkey">
            <br>
            <input type="submit" name="submitEnc" value="Zaszyfruj">
        </form>
        <form method="post">
            <label for="ciphertext">Wpisz tekst do odszyfrowania:</label>
            <textarea name="ciphertext" id="ciphertext" rows="4" cols="50"></textarea>
            <br>
            <label for="secretkey">Wpisz klucz szyfrujący:</label>
            <input type="text" name="secretkey" id="secretkey">
            <br>
            <input type="submit" name="submitDec" value="Odszyfruj">
        </form>
        <?php
        if (isset($operation) && isset($result)) {
            if ($operation == 'encrypt') {
                echo
                '<div class="result">
                    <label for="resulttext">Tekst zaszyfrowany:</label>
                    <textarea name="resulttext" id="resulttext" rows="4" cols="50">' . $result . '</textarea>
                </div>';
            } else {
                echo
                '<div class="result">
                    <label for="resulttext">Tekst odszyfrowany:</label>
                    <textarea name="resulttext" id="resulttext" rows="4" cols="50">' . $result . '</textarea>
                </div>';
            }
        }
        ?>
    </div>
</body>

</html>