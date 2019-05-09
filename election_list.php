<?php session_start();?>
<?php
include "auth.php";
include "header.php";
include "sql.php";
print_head();

$auth = new auth; 

$elecDataAccess = new get_election_list;
$elec_list = $elecDataAccess->getCurrentElecList();
?>

<body>
	<div class="header">
		<h1>近期選舉一覽</h1>
		<h2>Ravatae</h2>
	</div>
	<div class = "content">
		<div class = "table-responsive">
			<table class = "mq-table pure-table">
				<thead>
					<tr>
						<td>選舉名稱</td>
						<td>選區</td>
						<td>開始日期</td>
						<td>結束日期</td>
						<td>前往投票</td>
					</tr>
				</thead>
				<tbody>
                    <?php
                    $odd = true; //偶數序數項目
                    foreach($elec_list as $key => $elec){ //掃描逐個選舉
                        //決定該項背景（深色、淺色）
						$odd = !$odd;
						if ($odd) $table_class = "class = 'pure-table-odd'";
                        echo "<tr $table_class>";

                        //輸出該次選舉內容
                        foreach($elec as $col => $value){
                            if ($col ==0) continue;
                            echo "<td>$value</td>";
                        }
                        echo "</tr>";
                    }?>
            </tbody>
        </table>