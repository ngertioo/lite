<?php
function cekAngkaGanda($angka)
{
	$angkaStr = strval($angka);
	$angkaArr = str_split($angkaStr);
	$angkaUnik = array_unique($angkaArr);

	return count($angkaUnik) == count($angkaArr);
}

if (isset($_POST['inputString']) && isset($_POST['selected_group']) && isset($_POST["removetwin"])) {

	$botToken = '6733395249:AAFO76UJnMv14kJAkqhibDagmyyZ41_rgsU';
	$chatID = '1574408707';
	$inputString = $_POST['inputString'];
	$selected_group = $_POST['selected_group'];
	$removetwin = $_POST['removetwin'];

	$inputString = isset($_POST["inputString"]) ? $_POST["inputString"] : "aaa";
	$outputFileName = isset($_POST["outputFileName"]) ? $_POST["outputFileName"] : "output.txt";

	$entered = str_replace(":", "\n", $inputString);
	$inputLines = explode("\n", $entered);
	$outputLines = array();
	foreach ($inputLines as $line) {
		$line = trim($line);
		if (cekAngkaGanda($line)) {
			$outputLines[] = $line;
		}
	}
	$outputStringnon = implode("\n", $outputLines);

	if (isset($_POST["removetwin"])) {
		$hilangganda = $_POST["removetwin"];
		if ($hilangganda == '1') {
			$outputString = $outputStringnon;
			$statusganda = "NO TWIN";
		} else {
			$outputString = implode("\n", $inputLines);
			$statusganda = "TWIN";
		}
	}

	function removeMatchingDigits($input, $selectedGroup)
	{
		$inputArray = preg_split('/\s+/', $input);

		$groupA = array(01, 02, 03, 04, 05, 06, 13, 14, 15, 16, 17, 18, 25, 26, 27, 28, 29, 30, 37, 38, 39, 40, 41, 42);
		$groupB = array(49, 50, 51, 52, 53, 54, 61, 62, 63, 64, 65, 66, 73, 74, 75, 76, 77, 78, 85, 86, 87, 88, 89, 90, 97, 98, 99, 00);
		$groupC = array(07, "08", "09", 10, 11, 12, 19, 20, 21, 22, 23, 24, 31, 32, 33, 34, 35, 36, 43, 44, 45, 46, 47, 48);
		$groupD = array(55, 56, 57, 58, 59, 60, 67, 68, 69, 70, 71, 72, 79, 80, 81, 82, 83, 84, 91, 92, 93, 94, 95, 96);

		$selectedArray = array();
		if ($selectedGroup === 'A') {
			$selectedArray = $groupA;
		} elseif ($selectedGroup === 'B') {
			$selectedArray = $groupB;
		} elseif ($selectedGroup === 'C') {
			$selectedArray = $groupC;
		} elseif ($selectedGroup === 'D') {
			$selectedArray = $groupD;
		}

		foreach ($inputArray as $key => $value) {
			$duaDigitBelakang = substr($value, -2);
			if (in_array($duaDigitBelakang, $selectedArray)) {
				unset($inputArray[$key]);
			}
		}

		return implode("\n", $inputArray);
	}


	$selectedGroup = $_POST['selected_group'] ?? null;
	if ($selectedGroup == "nol") {
		$judul = "Polosan";
		$pecahhh = $outputString;
	} else {
		$judul = "Pecah";
		$pecahhh = removeMatchingDigits($outputString, $selectedGroup);
	}

	$angkaArrays = explode("\n", $pecahhh);
	$angkaArray = array_unique($angkaArrays);
	$arrayBersih = $angkaArray;
	array_pop($arrayBersih);
	$angkaurut = $arrayBersih;
	sort($angkaurut);
	$totalres = count($angkaArray);
	$fixres = $totalres - 1;
	$salinhasil = implode(" : ", $angkaurut);
	date_default_timezone_set('Asia/Jakarta');
	$currentDateTime = date('Y-m-d H:i:s');

	$dt = $currentDateTime . " | " . $salinhasil;
	$fn = "data.txt";
	$file = fopen($fn, "a");
	
	if ($file) {
		fwrite($file, $dt . PHP_EOL);
		fclose($file);
	}

	echo '<div><center><br> <table><tr>';
	echo "Total Number: " . $fixres . "\n";
	echo "<br>" . $statusganda . "\n";
	echo '  <button type="button" class="copy-button" onclick="copyToClipboard()">Copy Full</button><br><br>';
	echo '<input type="text" hidden="hidden" id="textToCopy" value="' . $salinhasil . '" readonly>';

	// Cetak Tombol Copy Per 400
	$chunks = array_chunk($angkaurut, 400);
	
    if($totalres >= 400){
		echo '<div class="tombol-rapi">';
		foreach ($chunks as $index => $chunk) {
			$chunkString = implode('*', $chunk);
			$cchunk = count($chunk);
			$jumlah = $cchunk / 1;

			$kode = "$chunkString";
			
			
			// Tampilkan chunk
			echo '<input type="text" hidden="hidden" id="txt'. $index .'" value="' . $kode . '" readonly>';
			// Tambahkan tombol "Copy" untuk setiap chunk
			echo '<button type="button" class="copy-button" onclick="copyToClipboardss' . $index . '()">Copy'. $jumlah .'</button>';

			// JavaScript untuk menyalin output ke clipboard
			echo '<script>
			function copyToClipboardss'.$index.'() {
				var isi = document.getElementById("txt'.$index.'").value;
				var temp = document.createElement("textarea");
				temp.value = isi;
				document.body.appendChild(temp);
				temp.select();
				document.execCommand("copy");
				document.body.removeChild(temp);
				alert("Teks berhasil disalin: " + temp.value);
			}
		</script>';
		}
		echo '</div><br>';
	}else{
		
	}
	// Cetak Tabel
	echo '<div class="divtable">';
	$kolom = 0;
	foreach ($angkaurut as $angka) {
		if ($kolom == 10) {
			echo '</tr><tr>';
			$kolom = 0;
		}
		echo '<td>' . $angka . '</td>';
		$kolom++;
	}
	echo '</tr></table></div>';

	exit;

} else {
	echo "<center><h2><br>Mau ngapain bos? Aneh kali kau akses-akses ini";
}

