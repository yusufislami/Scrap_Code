<?php

// URL untuk login ke akun BRI
$login_url = "https://ebanking.bankbri.co.id/login";

// Parameter untuk login, sesuaikan dengan informasi akun Anda
$username = "username";
$password = "password";
$post_data = "user_id=" . urlencode($username) . "&password=" . urlencode($password);

// Inisialisasi cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $login_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");

// Kirim permintaan login
$result = curl_exec($ch);

// URL untuk melihat mutasi
$mutasi_url = "https://ebanking.bankbri.co.id/AccountStatement.do";

// Set cURL options untuk melihat mutasi
curl_setopt($ch, CURLOPT_URL, $mutasi_url);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");

// Kirim permintaan untuk melihat mutasi
$result = curl_exec($ch);

// Parsing data HTML menggunakan DOMDocument
$dom = new DOMDocument();
@$dom->loadHTML($result);

// Ambil tabel mutasi
$xpath = new DOMXPath($dom);
$table = $xpath->query("//table[@class='table-mutasi']")->item(0);

// Ambil semua baris pada tabel
$rows = $table->getElementsByTagName("tr");

// Print header tabel
$header = array();
foreach ($rows[0]->getElementsByTagName("th") as $th) {
    $header[] = trim($th->nodeValue);
}
echo implode("\t", $header) . "\n";

// Print data mutasi
for ($i = 1; $i < $rows->length; $i++) {
    $data = array();
    foreach ($rows[$i]->getElementsByTagName("td") as $td) {
        $data[] = trim($td->nodeValue);
    }
    echo implode("\t", $data) . "\n";
}

// Tutup cURL
curl_close($ch);

?>