<DOC
config_file="docgen/doc_config.inc"
title="CHE Contract Register"
subject="Consultant Detail Report"
author="Octoplus Information Systems"
manager=""
company="Council on Higher Education"
operator=""
category="Report"
keywords=""
comment=""
>

<?php echo echo $xml_cover; ?>

<section landscape="yes">

	<header><b>Contract Register - Contracts Expiring Report</b></header>

	<footer>
		<table border="0">
		<tr>
			<td align="left">
				<font size="10"><b>Council on Higher Education</b><tab /></font></td><td align="right"><cpagenum />/<tpagenum />
			</td>
		</tr>
		</table>
	</footer>

	<?php echo echo $xml_main; ?>

</section>

</DOC>
