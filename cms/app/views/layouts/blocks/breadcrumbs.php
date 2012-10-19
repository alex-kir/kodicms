<?php if($breadcrumbs->count() > 1):?>
<ul class="breadcrumb">
<?php $i = 1; foreach($breadcrumbs as $breadcrumb): ?>
	<li>
		<?php if( ! empty($breadcrumb->url) AND $i < $breadcrumbs->count()): ?>
		<?php echo HTML::anchor( $breadcrumb->url, $breadcrumb->name ); ?>
		<?php else: ?>
		<?php echo $breadcrumb->name; ?>
		<?php endif; ?>
		<?php if($i < $breadcrumbs->count()): ?>
		<span class="divider">></span>
		<?php endif; ?>
	</li>
	<?php $i++; ?>
	<?php endforeach; ?>
</ul>
<?php endif; ?>