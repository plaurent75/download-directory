<?php $this->setPostViews(get_the_ID()); ?>
<?php if ( is_active_sidebar( 'down_repo_downloading' ) ) : ?>
	<div id="down_repo_downloading_widget" class="widget-area" role="complementary">
		<?php dynamic_sidebar( 'down_repo_downloading' ); ?>
	</div>
<?php endif; ?>
<h2><?php _e('Most popular downloads','down_repo') ?></h2>
<?php
remove_filter( 'the_title',array($this,'add_cat_to_title'), 10 );
remove_filter( 'post_thumbnail_html',array($this,'no_thumb'), 99 );
$args = array(
	'post_type' => 'down_repo',
	'meta_key' => 'down_repo_count',
	'orderby' => 'meta_value',
	'order' => 'DESC',
	);
$loop = new WP_Query( $args );
?>
<div class="down_repo_popular">
	<?php
	while ( $loop->have_posts() ) : $loop->the_post();
	?>
	<?php the_post_thumbnail('thumbnail',array('class'=>'alignleft')); ?>
	<h3 class="entry-title"><?php the_title(); ?></h3>
	<?php the_excerpt(); ?>
	<p><a href="<?php the_permalink() ?>" title="<?php _e('Download','down_repo') ?> <?php the_title_attribute(); ?>"><?php _e('Download','down_repo') ?> <?php the_title(); ?></a></p>
	<hr />
	<?php
	endwhile;
	wp_reset_query();
	?>
</div>
