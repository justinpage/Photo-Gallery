	</div>
	<div id="footer">Copyright <?=date("Y", time());?>, Justin Page</div>
</body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>