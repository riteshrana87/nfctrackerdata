<?php 
	$reportTypeTab = array(
		'pp' => array (
					'selectType' => 'PP',
					'selectURL' => base_url($this->type.'/Reports/PP')
				),
		'ibp' => array (
					'selectType' => 'IBP',
					'selectURL' => base_url($this->type.'/Reports/IBP')
				),
		'ra' => array (
					'selectType' => 'RA',
					'selectURL' => base_url($this->type.'/Reports/RA')
				),
		'do' => array (
					'selectType' => 'DO',
					'selectURL' => base_url($this->type.'/Reports/DOS')
				),
		'ks' => array (
					'selectType' => 'KS',
					'selectURL' => base_url($this->type.'/Reports/KS')
				),
		'docs' => array (
					'selectType' => 'DOCS',
					'selectURL' => base_url($this->type.'/Reports/DOCS')
				),
		'meds' => array (
					'selectType' => 'MEDS',
					'selectURL' => base_url($this->type.'/Reports/MEDS')
				),	
		'coms' => array (
					'selectType' => 'COMS',
					'selectURL' => base_url($this->type.'/Reports/COMS')
				),	
	);
?>
<div class="nav-buttons">
    <ul class="nav nav-pills nav-justified">
	
		<?php foreach ($reportTypeTab as $rKey => $rValue) {
		?>			
			<li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == $rKey) { ?>class="active"<?php } ?>>
				<a href="<?= $rValue['selectURL']; ?>"><i class="fa fa-file-text"></i><?=$rValue['selectType']; ?></a>
			</li>
		<?php }?>
    </ul>
</div>