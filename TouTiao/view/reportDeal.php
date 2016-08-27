
<?php
require_once '../model/common.php';
require_once '../model/constant.php';
require_once '../dao/abstractDao.php';
require_once '../dao/commomDao.php';
$dao = new CommonDao ();

$offset = $_GET ["offset"];
$num = $_GET ["num"];
$news = $dao->getReportNewsTitle ( $offset, $num );
if ($news)
	echo json_encode ( $news );
else
	echo 0;

?>		

