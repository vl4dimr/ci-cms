<br class="clearfloat" />

</div></div>
<!-- [Main] end -->

<!-- [Footer] start -->
<div id="footer"><br />Page rendered in {elapsed_time} seconds
</div>
<!-- [Footer] end -->

</div>
<!-- [Base] end -->
<?php if ($this->system->debug == 1) : ?>
<div>
<table width='100%'>
	<tr>
		<th>Time</th>
		<th>Query</th>
	</tr>
<?php 
foreach ($this->db->queries as $key => $val)
{
	$val = htmlspecialchars($val, ENT_QUOTES);
	$time = number_format($this->db->query_times[$key], 4);
?>	
	<tr>
		<td align='left'><?=$time?></td>
		<td align='left'><?=$val?></td>
	</tr>
<?php
}
?>
</table>
</div>
<?php endif; ?>
</body>
</html>
