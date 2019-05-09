<?php session_destroy();?>
<?php
include ("header.php");
include ("sql.php");
print_head(); //defined in header.php

$dbHost = "localhost";
$dbUName = "root";
$dbpwd = "vote_max0804";
$dbName = "ravatae";

$elecDataAccess = new get_election_list($dbHost, $dbUName , $dbpwd, $dbName);
$elec_list = $elecDataAccess->getCurrentElecList();
?>
<body>
	<div class = "header">
		<h1>Ravatae</h1>
		<h2>國立東華大學學生會電子投票系統</h2>
	</div>

	<div class = "content">
		<h2 class = "content-subhead">近期選舉</h2>
		<table class = "pure-table">
			<thead>
				<tr>
					<td>選舉名稱</td>
					<td>選區</td>
					<td>開始日期</td>
					<td>結束日期</td>
				</tr>
			</thead>
			<tbody>
                <?php
                $odd = true; //偶數序數項目
                foreach($elec_list as $key => $elec){ //掃描逐個選舉
                    //決定該項背景（深色、淺色）
                    $odd = !$odd;
                    if($odd) echo "<tr class= 'pure-table-odd'>";
                    else echo "<tr>";

                    //輸出該次選舉內容
                    foreach($elec as $col => $value){
                        if ($col ==0) continue;
                        echo "<td>$value</td>";
                    }
                    echo "</tr>";
                }?>
            </tbody>
        </table>

        <h2 class = "content-subhead">登入系統</h2>
			<form method = "POST" action = "election_list.php">
				<div class = "pure-form pure-form-stacked">
					<input placeholder="帳號" type = "text" name ="uid" id="uid">
					<input placeholder="密碼" type = "password" name="pwd" id="pwd">
					<button type="submit" class="pure-button pure-button-primary">登入</button>
				</div>
			</form>
	</div>
</body>
</html>