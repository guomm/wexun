<?php
	interface AbstractFactory{
		function createModel();
		function createDao();
	}
?>