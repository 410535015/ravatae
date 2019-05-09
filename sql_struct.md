## student
記錄學生（選民）的資料，一位選民一筆record。
### id
學號。Unicode字串
### name
姓名。Unicode字串
### department_id
參考自department資料表的id欄位，學生所屬的系所。char(3)
### type
該生所屬學制。Unicode字串
### enroll
學生是否註冊。布林值
### 範例
id|name|department_id|type|enroll
---|---|---|---|---
410535015|吳書凡|資訊管理學系|學士班|TRUE
410792003|王致維|民族語言與傳播學系|碩士班|TRUE

## election
記錄選舉資料，一場選舉一筆record。選區、候選人不在此表紀錄。
### id
選舉號碼，Dec(6)
### title_zh
中文選舉名稱。Unicode字串
### title_en
英文選舉名稱。Unicode字串
### start_time
選舉起始時間。Datetime
### expert_time
選舉終止時間。Datetime
### info_zh
中文選舉、選制說明，以Markdwon格式紀錄。Unicode字串
### info_en
英文選舉、選制說明，以Markdown格式紀錄。Unicode字串
### note
備註。Unicode字串
### 範例
id | title_zh | title_en | start_time | expert_time | info_zh | info_en | note
---|---|---|---|---|---|---|---
000017|第2屆學生會執行長、副執行長選舉|2th Election of CEO & Vice CEO of Student Association|2019/04/01 08:00:00|2019/04/06 21:00:00|中華民國國民年滿20歲...|Citizen of Taiwan who be over 20...|無
000018|第2屆學生議會議員選舉|2th Election of councilmen of Student Council|2019/04/01 08:00:00|2019/04/06 21:00:00|中華民國國民年滿20歲...|Citizen of Taiwan who be over 20...|增額選舉

## district
紀錄靜態選區資料，一個選區一筆record。選舉、候選人不在此表紀錄。
### id
選區號碼，Dec(3)
### title_zh
中文選區名稱。Unicode字串
### title_en
英文選區名稱。Unicode字串
### note
備註。Unicode字串
id|title_zh|title_en|note
### 範例
id|title_zh|title_en|note
---|---|---|---
001|全校不分區|Entire Campus|無
002|理工學院|Colleage of Science & Engineerign|無

## department
紀錄靜態系所資料，一個科系、研究所一筆record。選區、選舉不在此表紀錄。
### id
系所代號，即學號中的系所代碼，原則上為二數字或一數字一字母，預留三個Unicode字元紀錄。Char(3)
### code
簡短代碼，即課程代碼中的系所代號，為二到五英文字母。Unicode字串
### title_zh
中文系所名稱。Unicode字串
### title_en
英文系所名稱。Unicode字串
### 範例
id|code|title_zh|title_en
---|---|---|---
35|IM|資訊管理學系|Dept. of Infomation Management
21|CSIE|資訊工程學系|Dept. of Computer Science & Information Engineering

## dist-dept
描述選區與系所間關係，當某系所與某選區出現在同一筆record內，則該系所屬於該選區。
### district_id
參考自district表中的id欄位，選區號碼。Dec(3)
### department_id
參考自department表中的id欄位，系所代號。Char(3)
### 範例
district_id|department_id
---|---
001|21
001|22
001|45
001|92
002|21
002|22

## elec-dist
描述選舉與選區之間的關係，以及該選區在當次選舉中的應選名額。當某選舉與某選區出現在同一筆record，則當次選舉包含該選區。
### id
選舉-選區對應關係編號。每次選舉、不同選區都會有各自的id。Dec(6)
### election_id
選舉號碼。Dec(6)
### district_id
選區號碼。Dec(3)
### numOfSeat
該選區在當次選舉中的應選名額。Dec(3)
### 範例
id|election_id|district_id|numOfSeat
---|---|---|---
000111|000017|001|1
000112|000018|001|30
000113|000018|002|5

## candidate
紀錄候選人資料，一位候選人一筆record，同一人參加不同選舉、同時在多個選區登記，應視為不同候選人。
### id
候選人代碼。這不是候選人在該次選舉的號次！Dec(6)
### student_id
候選人學號，參考自student資料表。Unicode字串
### elec-dist_id
紀錄候選人所屬的選舉、選區。參考自elec-dist資料表中的id欄位。Dec(6)
### number
候選人於當次選舉該選區中的抽籤序號，可以留空。Dec(3)
### politics
候選人的政見內容，以Markdwon格式儲存。Unicode字串
### numOfVote
候選人的得票數。這不是即時更新的。Dec(5)
### elected
當選標記。一次選舉的一個選區中，可能不止一位當選人。布林值
### 範例
id|student_id|elec-dist_id|number|politics|numOfvote|elected
---|---|---|---|---|---|---
003754|410535015|000111|1|BALABALA|1782|TRUE
003755|410592015|000112|2|HELLO|650|FALSE

## vote
紀錄選票資料，一張選票一筆record。
### id
選票號碼。Dec(10)
### student_id
投出該張選票的選民的學號，參考自student資料表。Unicode字串
### elec-dist_id
該張選票所屬的選舉、選區。參考自elec-dist的id欄位。Dec(6)
### candidate_id
該張選票投予的對象，參考自candidate資料表的id欄位。Dec(6)
### timestamp
時間戳記，紀錄收到投票請求的時間。Datetime
### ip
紀錄送出投票請求的IP位址。Unicode字串
### 範例
id|student_id|elec-dist_id|candidate_id|timestamp|ip
---|---|---|---|---|---
0000013610|410535015|000111|002102|2019/03/08 12:08:11|134.208.97.134

## admin
紀錄管理者的帳號資訊，一個帳號一筆record。
### username
管理者帳號的使用者名稱。Unicode字串
### passwd
管理者的登入密碼。以MD5加鹽方式紀錄。Unicode字串
### salt
密碼加鹽。Unicode字串
### permissionOfCaculating
計票權限。布林值
### permissionOfHold
舉辦、取消選舉權限。布林值
### permissionOfPersonnel
人事（帳號管理）權限。布林值
### blocked
停權狀態。布林值