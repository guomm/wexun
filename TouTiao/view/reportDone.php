
<?php
require_once '../model/common.php';
require_once '../model/constant.php';
require_once '../dao/abstractDao.php';
require_once '../dao/commomDao.php';
$dao = new CommonDao ();

$report_id = $_GET ["report_id"];
$news = $dao->dealReport ( $report_id );
if ($news)
	echo 1;
else
	echo 0;

?>		

