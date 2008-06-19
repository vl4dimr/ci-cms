
<form class="settings" action="<?=site_url('admin/settings')?>" method="post" accept-charset="utf-8">
		
		<ul>
			<li><input type="submit" name="submit" value="Save Settings" class="input-submit" /></li>
			<li><a href="<?=site_url('admin')?>" class="input-submit last">Cancel</a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />
		
		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?=$notice;?></p>
		<?php endif;?>
		
		<p>Use this page to change the global settings for your site.</p>
		
		<div id="one">
		
			<label for="site_name">Site Name:</label>
			<input type="text" name="site_name" value="<?=$this->system->site_name?>" id="site_name" class="input-text" /><br />
		
			<label for="meta_keywords">META keywords:</label>
			<input type="text" name="meta_keywords" value="<?=$this->system->meta_keywords?>" id="meta_keywords" class="input-text" /><br />
		
			<label for="description">META description:</label>
			<input type="text" name="meta_description" value="<?=$this->system->meta_description?>" id="meta_description" class="input-text" /><br />
			
			<label for="cache">Output Cache:</label>
			<div id="cache">
				<input <?php if ($this->system->cache == 1):?>checked="checked"<?php endif;?> type="radio" name="cache" value="1" /> On<br />
				<input <?php if ($this->system->cache == 0):?>checked="checked"<?php endif;?> type="radio" name="cache" value="0" /> Off<br />
			</div>
			
			<label for="cache_time">Cache Time:</label>
			<input type="text" name="cache_time" value="<?=$this->system->cache_time?>" id="cache_time" class="input-text" /><span>seconds</span><br />
		
		</div>
		<div id="three">
			<label for="theme">Theme:</label>
				<select name="theme" class="input-select" id="theme">
				<?php foreach ($themes as $theme):?>
					<option <?php if ($theme == $this->layout->theme):?>selected='selected' <?php endif;?>value="<?=$theme?>"><?=ucwords(str_replace('_', ' ', $theme))?></option>
				<?php endforeach;?>
				</select><br />
			<p><strong><?=__("Available block contents")?></strong></p>
			<dl>
					<?php foreach($available_partials as $module => $partials):?>
					<dt><em><?=ucfirst($module)?></em></dt>
						<?php foreach($partials as $partial): ?>
						<dd>
							<label for="blocks[<?=$module?>][<?=$partial?>]"><?=ucfirst(str_replace('_', ' ', $partial)) ?></label>
							
							<select name="blocks[<?=$module?>][<?=$partial?>]" class="input-select" id="block_<?=$partial?>">
								<option value=""><?=__("None")?>
							<?php for ($num = 1; $num <= $available_blocks; $num++):?>
								<option value="<?=$num?>"  <?=($this->layout->is_in_area('area.'.$num, $module, $partial))?"selected=\"selected\"":""?>>Block <?=$num?></option>
							<?php endfor; ?>
							</select>
						</dd>
						<?php endforeach; ?>
				<?php endforeach; ?>
			</dl>
			<?php 
			/*
			<?php for ($num = 1; $num <= $available_blocks; $num++):?>
			<label for="area_<?=$num?>">Block <?=$num?>:</label>
			<select name="area_<?=$num?>" class="input-select" id="area.<?=$num?>">
				<option <?php if (empty($this->layout->blocks['area.'.$num])):?>selected="selected"<?php endif;?> value="none">No Content</option>
				<?php foreach($available_partials as $module => $partials):?>
					<optgroup label="<?=ucfirst($module)?>">
						<?php foreach($partials as $partial): ?>
						<option <?php if (!empty($this->layout->blocks['area.'.$num]) && $this->layout->blocks['area.'.$num]['module'] == $module && $this->layout->blocks['area.'.$num]['method'] == $partial) :?>selected="selected"<?php endif;?> value="<?=$module?>|<?=$partial?>"><?=ucfirst(str_replace('_', ' ', $partial)) ?></option>
						<?php endforeach; ?>
					</optgroup>
				<?php endforeach; ?>
			</select><br />
			<?php endfor; ?>
			*/
			?>
		</div>
	</form>
