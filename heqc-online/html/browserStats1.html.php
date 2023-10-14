<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td>
	<b>Please click "Next" to determine your browser's capabilities.</b>
<?php 
	$this->showField("appCodeName");
	$this->showField("appMinorVersion");
	$this->showField("appName");
	$this->showField("appVersion");
	$this->showField("cookieEnabled");
	$this->showField("cpuClass");
	$this->showField("onLine");
	$this->showField("platform");
	$this->showField("systemLanguage");
	$this->showField("userAgent");
	$this->showField("userLanguage");
	$this->showField("javaEnabled");
	$this->showField("taintEnabled");
	$this->showField("mimeTypes[]");
	$this->showField("plugins[]");
?>
<br><br><br><br>
<br><br><br><br>
	</td>
</tr></table>
<br><br>
</td></tr></table>
<script>
		function initForm (obj) {
			obj.appCodeName.value = navigator.appCodeName;
			obj.appMinorVersion.value = navigator.appMinorVersion;
			obj.appName.value = navigator.appName;
			obj.appVersion.value = navigator.appVersion;
			obj.cookieEnabled.value = navigator.cookieEnabled;
			obj.cpuClass.value = navigator.cpuClass;
			obj.onLine.value = navigator.onLine;
			obj.platform.value = navigator.platform;
			obj.systemLanguage.value = navigator.systemLanguage;
			obj.userAgent.value = navigator.userAgent;
			obj.userLanguage.value = navigator.userLanguage;
			obj.javaEnabled.value = navigator.javaEnabled();
			obj.taintEnabled.value = navigator.taintEnabled();
			if (navigator.mimeTypes) {
				for (i=0; i < navigator.mimeTypes.length; i++) {
					obj.mimeTypes[i].value = navigator.mimeTypes[i].type;
				}
			}
			if (navigator.plugins) {
				for (i=0; i < navigator.plugins.length; i++) {
					obj.plugins[i].value = navigator.plugins[i].name;
				}
			}
		}
	</script>