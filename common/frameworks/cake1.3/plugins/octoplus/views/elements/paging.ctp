<?php
	if(isset($this->Paginator))
	{
		$this->Html->css('/octoplus/css/paginator.css', null, array('inline' => false));
		echo '<div class="paginator">';
		echo $this->Paginator->prev(__d('octoplus', '<< Previous ', true), null, __d('octoplus', '<< Previous ', true), array('class' => 'disabled'));
		echo $this->Paginator->numbers(array('first' => 3, 'last' => 3, 'separator' => ' ', 'modulus' => 4));
		echo $this->Paginator->next(__d('octoplus', ' Next >>', true), null, __d('octoplus', ' Next >>', true), array('class' => 'disabled'));

		$params = $this->Paginator->params();
		if($params['count'] > 10 && !isset($hideLimiter))
		{
			$limits = array(
				10 => 10,
				20 => 20,
				50 => 50,
				100 => 100,
				250 => 250,
			);
			
			echo '<div class="limitor">';
				echo $this->Form->input('limit', array('id' => 'pageLimiter', 'label' => __d('octoplus', 'Display: ', true), 'options' => $limits, 'selected' => $params['options']['limit']));
			echo '</div>';
			?>
			<span id="pagingLimitUrl" style="display:none;"><?php 
							$params = $this->params;
							$urlParams = $params['named'];
							
							if(isset($extraParams)) {
								foreach($extraParams as $extraParam) {
									if(isset($params[$extraParam])){
										$urlParams[$extraParam] = $params[$extraParam];
									}
								}
							}
							
							if(!empty($params['pass'])) {
								$urlParams = array_merge($urlParams, $params['pass']);
							}
							
							echo $this->Html->url(array_merge($urlParams, array('limit' => '#')))
						?></span>
			<script type="text/javascript">
				$(function(){
					$('#pageLimiter').change(function(){
						var limitUrl = $('#pagingLimitUrl').text();
						window.location = limitUrl.replace('#', $(this).val());
					});
				});
			</script>
			<?php
		}
		echo '</div>';
	}
