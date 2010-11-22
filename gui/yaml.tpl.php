<?php
/**
 * Citation templates' generator.
 *
 * @addtogroup Skins
 * @author Michał "Hołek" Połtyn
 * @copyright © 2009 Michał Połtyn
 * @license GNU General Public Licence 2.0 or later
 */

?>
---
query:
<?php for ($i=0;$i<count($this->input) && $i<6;$i++) : ?>
  input<?php echo $i; ?>: "<?php $this->eprint($this->input[$i]); ?>"
<?php endfor; ?>
response:
<?php
if (count($this->bookshelf)) :
$i = 0;
foreach ($this->bookshelf as $book) : ?>
  -
    source: "<?php $this->eprint($this->sources[$i++][0]); ?>"
    content: |
      "<?php $this->eprint(str_replace("\n","\n      ",$book)); ?>"
<?php endforeach;
endif; ?>
paramlist:
<?php foreach($this->settings as $key => $value) : ?>
  <?php $this->eprint($key); ?>: <?php echo (($value)?'y':'n')."\n"; ?>
<?php endforeach; ?>
