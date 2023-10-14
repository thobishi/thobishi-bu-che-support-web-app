<?php
class FilterHelper extends AppHelper {
	public $helpers = array('Html', 'Form', 'Text');

	public function filter ($title, $field = null, $options = null, $linkOptions = array()) {
		if(empty($field)) {
			$field = $title;
			$title = __d('octo_filter', Inflector::humanize(preg_replace('/_id$/', '', $title)), true);
		}
		
		$viewVariables = ClassRegistry::getObject('View')->viewVars;
		$viewVariable = Inflector::pluralize(preg_replace('/_id$/', '', $field));
		if(isset($viewVariables[$viewVariable]) || !empty($options)) {
			$linkClass = 'filterLink';
			
			if(isset($this->params['named'][$field])) {
				$linkClass .= ' filter-active';
			}
					
			$linkOptions = array_merge(
				array('class' => $linkClass, 'title' => sprintf(__d('octo_filter', 'Filter by %s', true), strtolower($title))),
				$linkOptions
			);
			
			$return = $this->Html->link($title, '#', $linkOptions);
		
			if(empty($options)) {
				$options = $viewVariables[$viewVariable];
			}
			
			$filterField = $this->Form->input($field, array(
				'div' => array(
					'class' => 'filterCheckboxes'
				),
				'class' => 'filterCheckbox', 
				'label' => false, 
				'multiple' => 'checkbox', 
				'options' => $options
			));
			
			$return .= '<div class="filterBox ui-widget ui-widget-content ui-corner-all">';
			$return .= '<div class="ui-widget-header ui-corner-all">';
			$return .= '<input class="filterInput" placeholder="'.__d('octo_filter', 'Search within list', true).'" type="text" />';
			$return .= '</div>';
			$return .= $filterField;
			
			$return .= '<div class="filterButtons bulkButtons">';
				$return .= $this->Form->button(__d('octo_filter', 'Apply search', true));
				$return .= $this->Form->button(__d('octo_filter', 'Clear search', true), array('class' => 'clearFilter'));
			$return .= '</div></div>';
			
			$this->Html->script(array(
				'/octo_filter/js/filter',
			), array('inline' => false));
			$this->Html->css(array(
				'/octo_filter/css/filter',			
			), null, array('inline' => false));
		}
		else {
			$return = $title;
		}
		
		return $return;
	}
	
	public function startForm($model) {
		return $this->Form->create($model, array('url' => $this->params['named']));
	}
	
	public function endForm() {
		return $this->Form->end();
	}
	
	public function search($text) {
		$output = '';
		
		$output .= $this->Form->input('search', array(
			'div' => false,
			'placeholder' => $text,
			'label' => false,
			'class' => 'inline short'
		));

		$output .= $this->Form->button(__d('octo_filter', 'Go', true), array('class' => 'submitButton'));
		$output .= $this->Form->button(__d('octo_filter', 'Clear', true), array('id' => 'clearSearch'));
		
		return $output;
	}
	
	public function activeFilters($model) {
		$output = '';
		
		if(!empty($this->data[$model])) {
			$data = $this->data[$model];
			
			$filters = array();
			foreach($data as $fieldName => $values) {
				if(is_array($values)) {
					$filters[] = strtolower(Inflector::humanize(Inflector::pluralize(preg_replace('/_id$/', '', $fieldName))));
				}
				else {
					$filters[] = sprintf(__d('octo_filter', 'items matching "%s"', true), $values);
				}
			}
			
			if(!empty($filters)) {
				sort($filters);

				$output .= sprintf(__d('octo_filter', 'Currently searching by %s.', true), $this->Text->toList($filters, __d('octo_filter', 'and', true))) . '<br />';
				$output .= $this->Form->button(__d('octo_filter', 'Clear all search terms', true), array('id' => 'clearFilters'));
			}
		}
		
		return $output;
	}
	
	public function filterBlock($options = array()) {
		var_dump('bla');
		$output = '<div id="filterBlock">';
		$output .= $this->__startFilterForm();
		$output .= $this->__blockHeader($options);
		$output .= $this->__filterBody($options);
		$output .= $this->__filterFooter($options);
		$output .= $this->Form->end();
		$output .= $this->__availableFilters($options);
		$output .= '</div>';
		
		$this->Html->script(array(
			'/octo_filter/js/filter_block',
			'/jquery_plugins/js/jquery.multiselect',
			'/jquery_plugins/js/jquery.multiselect.filter.min',
			'/jquery_plugins/js/daterangepicker.jQuery',
		), array('inline' => false));
		$this->Html->css(array(
			'/octo_filter/css/filter_block',
			'/jquery_plugins/css/jquery.multiselect',
			'/jquery_plugins/css/jquery.multiselect.filter',
			'/jquery_plugins/css/ui.daterangepicker',			
		), null, array('inline' => false));
		
		return $output;
	}
	
	private function __dateRange($options) {
		$options = Set::merge($options, array(
			'name' => 'date_range',
			'label' => __d('octo_filter', 'Date range: ', true),
			'fieldOptions' => array(
				array(
					'type' => 'date',
					'label' => false,
					'div' => array('id' => 'DateRange0'),
					'separator' => '',
					'empty' => '',
				),
				array(
					'type' => 'date',
					'label' => false,
					'div' => array('id' => 'DateRange1'),
					'separator' => '',
					'empty' => '',
				),
			)
		));
		
		$output = '<label>'.$options['label'].'</label>';
		$output .=  $this->Form->input($options['name'] . '.0', $options['fieldOptions'][0]);
		$output .= $this->Form->input($options['name'] . '.0', $options['fieldOptions'][1]);
		
		return $output;
	}
	
	private function __multiple($options) {
		return $this->Form->input(
				$options['name'],
				array(
					'multiple' => true,
					'label' => $options['label'],
					'options' => $options['options'],
					'div' => false
				)
			);
	}
		
	private function __availableFilters($options) {
		$output = '<ul id="availableFilters" class="ui-helper-hidden">';

		foreach($options['availableFilters'] as $filter) {
			if(method_exists($this, '__' . $filter['type'])) {
				if(!isset($filter['listOptions'])) { $filter['listOptions'] = array(); }
				$output .= $this->Html->tag('li', null, 
					array_merge(
						$filter['listOptions'],
						array(
							'title' => $filter['title']
						)
					)
				);
				$output .= $this->{'__' . $filter['type']}($filter);
				$output .= '</li>';
			}
		}
		
		$output .= '</ul>';
		
		return $output;
	}
	
	private function __filterFooter($options) {
		$output = '<div id="filterFooter" class="ui-widget-header ui-corner-all">';
		$output .= $this->Form->input('filterList', array(
			'div' => array('id' => 'addFilter'),
			'label' => false,
			'class' => 'redraw',
			'id' => 'filterSelect',
			'name' => '',
			'options' => array(),
			'empty' => __d('octo_filter', '-- Select filter to add --', true)
		));

		$output .= '<div class="clear"></div>';
		$output .= '</div>';
		
		return $output;
	}
	
	private function __filterBody($options) {
		$output = '<ul id="activeFilters">';
		if(!empty($options['filterOptions'])) {
			$output .= '<li id="filterOptions" class="reportOptions filter">';
		
			foreach($options['filterOptions'] as $filterName => $filterOption) {
				$output .= $this->Form->input($filterName, $filterOption);
			}
			
			$output .= '</li>';
		}
		$output .= '</ul>';
		
		return $output;
	}
	
	private function __blockHeader($options) {
		$output = '<div id="filterHeader" class="ui-widget-header ui-corner-all"><div id="filterActions">';
		$output .=  $this->Html->link(
			__d('octo_filter', 'Save filters', true),
			array(
				'plugin' => 'octo_filters',
				'controller' => 'filters',
				'action' => 'add'
			),
			array(
				'id' => 'saveFilter',
				'class' => 'dialog',
				'title' => __d('octo_filter', 'Save filters', true)
			)
		);
		$output .=  $this->Html->link(
			__d('octo_filter', 'Load saved filter', true),
			array(
				'plugin' => 'octo_filters',
				'controller' => 'filters',
				'action' => 'load'
			),
			array(
				'id' => 'loadFilter',
				'class' => 'dialog',
				'title' => __d('octo_filter', 'Load saved filter', true)
			)
		);
		$output .= '</div><span>'.__d('octo_filter', 'Filters', true) . '</span><div class="clear"></div></div>';
		
		return $output;
	}
	
	private function __startFilterForm() {
		return $this->Form->create('Filter', array(
			'id' => 'filterForm', 
			'url' => array('ext' => 'json'),
			'class' => 'ui-widget ui-widget-content ui-corner-all'
		)); 
	}
}