<?php
/**
 * Citation templates' generator.
 *
 * @addtogroup Skins
 * @author Michał "Hołek" Połtyn
 * @copyright © 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */
 echo '<'.'?xml version="1.0" encoding="utf-8"?'.'>';
?>

<wikitool application="cite">
<query>
	<?php for ($i=0;$i<count($this->input) && $i<6;$i++) : ?>
	<input><?php $this->eprint($this->input[$i]); ?></input>
	<?php endfor; ?>
</query>
<response>
<?php
if (count($this->bookshelf)) :
$i = 0;
foreach ($this->bookshelf as $book) : ?>
	<template>
		<source><?php echo $this->eprint($this->sources[$i++][0]); ?></source>
		<content><?php echo $this->eprint($book); ?></content>
	</template>
<?php endforeach;
endif; ?>
<paramlist>
	<?php foreach($this->settings as $key => $value) : ?>
	<param id="<?php echo $this->eprint($key); ?>" value="<?php echo (($value)?'1':'0'); ?>" />
	<?php endforeach; ?>
</paramlist>
</response>
</wikitool>
