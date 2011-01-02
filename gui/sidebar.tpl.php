<!doctype html>
<html lang="<?php echo $this->scriptLanguage; ?>" class="lang-<?php echo $this->scriptLanguage; ?>">
<head>
	<title><?php echo $this->lang['Sidebar-title']; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="Michal Poltyn" name="author" />
	<link rel="icon" type="image/png" href="gui/monobook/favicon.png" />
	<script type="text/javascript">
		function runMethod(checkbox)
		{
			switch (checkbox)
			{
				case 'add-list':
					if (document.getElementById('add-list').checked==false) {
						if (document.getElementById('add-references').checked == true) {
							document.getElementById('output').value = document.getElementById('output').value.replace(/\* <ref/g, '<ref');
						}
						else {
							document.getElementById('output').value = document.getElementById('output').value.replace(/\* \{\{/g, '{{');
						}
					}
					else {
						if (document.getElementById('add-references').checked == true) {
							document.getElementById('output').value = document.getElementById('output').value.replace(/<ref/g, '* <ref');
						}
						else {
							document.getElementById('output').value = document.getElementById('output').value.replace(/\{\{/g, '* {{');
						}
					}
				break;
				case 'add-references':
					if (document.getElementById('add-references').checked==false) {
						output = document.getElementById('output').value.replace(/<ref(| name="(.*?)")>/g, '');
						output = output.replace(/<\/ref>/g, '');
						document.getElementById('output').value = output;
					}
					else {
						output = document.getElementById('output').value.replace(/\{\{/g, '<ref>{{');
						output = output.replace(/\}\}/g, '}}</ref>');
						document.getElementById('output').value = output;
					}
					break;
				case 'append-newlines':
					if (document.getElementById('append-newlines').checked==false) {
						document.getElementById('output').value = document.getElementById('output').value.replace(/\n\| /g, ' | ');
					}
					else {
						document.getElementById('output').value = document.getElementById('output').value.replace(/ \| /g, "\n| ");
					}
					break;
				default:
				break;
			}
		}
	</script>
	<style type="text/css">
		html {
			background: #f9f9f9 url(gui/monobook/headbg.jpg) 0 0 no-repeat;
			color: black;
			font-family: sans-serif;
		}
		body {
			font-size:90%;
		}
		h2, h3, h4, h5 {
			margin: 0;
			padding-top: .5em;
			padding-bottom: .17em;
		}
		h2, h5 {
			font-weight: normal;
			border-bottom: 1px solid #aaa;
		}
		hr { height: 1px; background-color: #aaa; border: 0; margin-top: 2em; }
		a { text-decoration: none; color: #002bb8; }
		a:visited {color: #5a3696; }
		a:active { color: #faa700; }
		a:hover { text-decoration: underline; }
		img { border: none; vertical-align: middle; margin: 0;}	
		#content {
			margin-top: .8em;
			padding: 0 1em 1em 1em;
			background: #fff;
			border: 1px solid #aaa;
			line-height: 1.5em;
			z-index: 2;
		}
		ul { line-height: 1.5em; list-style-type: square; list-style-image: url(gui/monobook/bullet.gif); font-size: 95%; padding-left:2em; margin:0.3em; }
		li { padding: 0; margin: 0;}
		label { font-size: 85%; }
	</style>
</head>
<body>
	<div id="content">
<?php if (isset($this->veryImportantMessage) && trim($this->veryImportantMessage) ) : ?> 
		<div style="margin:10px 0 5px;border:3px dashed #F00;background-color:#FFC0CB;padding:5px 10px"><?php echo $this->veryImportantMessage; ?></div>
<?php endif; ?>
		<h2><a href="<?php echo $_SERVER['PHP_SELF']; ?>?template=monobook" target="_content"><?php echo $this->lang['Sidebar-title']; ?></a></h2>
		<form action="redirect.php" method="get">
			<?php for ($i=0;$i<6;$i++) : ?>
			<input name="input[]" value="<?php $this->eprint($this->input[$i]); ?>" type="text" style="width: 100%" /><br/>
			<?php endfor; ?>
			<?php foreach ($this->availableSettings as $setting) : ?>
			<input onchange="runMethod('<?php echo $setting; ?>');" name="s[<?php echo $setting; ?>]" id="<?php echo $setting; ?>" type="checkbox"<?php if ($this->settings[$setting] == true) echo ' checked="checked"'; ?> />
			<label for="<?php echo $setting; ?>"><?php $this->eprint($this->lang['Option-'.$setting]); ?></label><br/>
			<?php endforeach; ?>
			<input type="submit" value="<?php $this->eprint($this->lang['Send']); ?>"/>
			<input type="hidden" name="template" value="sidebar" />
		</form>
<?php   if (count($this->bookshelf)) : ?>
		<h2><?php echo $this->lang['Output-title']; ?></h2>
		<textarea style="width:100%;" rows="16" cols="30" id="output"><?php
		foreach ($this->bookshelf as $book) :
			echo $this->eprint($book)."\n";
		endforeach; ?></textarea>
		<h3><?php echo $this->lang['Sources-title']; ?></h3>
		<ul>
<?php	foreach ($this->sources as $source) : ?>
		<li><a href="<?php $this->eprint($source[0]); ?>" target="_blank"><?php $this->eprint($source[1]); ?></a> (<?php echo $source[2]['parser'].' '.$source[2]['data']; ?>)</li>
<?php	endforeach; ?>
		</ul>
<?php	endif; ?>
	</div>
</body>
</html>
