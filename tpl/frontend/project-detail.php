<?php
/*********************************************************
 * Custom template for displaying a single project
 *********************************************************/
global $post, $projects_manager;

//Get the primary image for this project
$img = $projects_manager->post->meta['project-manager-featured']==0 ? $projects_manager->post->meta['project-manager-images'][0] : $projects_manager->post->meta['project-manager-images'][$projects_manager->post->meta['project-manager-featured']-1];

//Get the logo image
$logo = isset($post->meta['project-manager-logo']) && !empty($post->meta['project-manager-logo']) ? $post->meta['project-manager-logo'] : null;

//Convert the URL to a file system path
$fs_logo = !empty($logo) ? $projects_manager->url_to_fs_path($logo) : $logo;

//Verify the file exists
$logo = file_exists($fs_logo) ? $logo : null;

//Include the theme header
get_header();
?>

<section id="content-container" class="project-detail-container">
	<section id="content-body">
		<h2><?php echo (!empty($logo)) ? '<img src="'.$logo.'" data-src="'.$logo.'" /></h2>' : ''; ?></h2>
		<section id="project-detail-body">
			<div class="project-detail-text">
				<a href="#" class="project-detail-trigger project-type-<?php echo implode(' project-type-', $post->meta['type_slugs']); ?>" data-id="<?php echo $post->ID; ?>" style="background: url('<?php echo $img; ?>') no-repeat center center; background-color: #fff; background-size: contain; display: block; text-indent: -9999px;"><img src="<?php echo $img; ?>" class="bg-helper"></a>
				<h2><?php echo empty($post->meta['project-manager-description']) ? $post->post_title : $post->meta['project-manager-description']; ?></h2>
				<?php echo wptexturize(wpautop(do_shortcode($post->post_content))); ?>
			</div>
                        <?php @include('sidebar.php'); ?>
		</section>
                <section id="project-scroller">
                    <?php echo do_shortcode('[project_manager_display size="double"]') ?>
                </section>
	</section>
</section>

<?php //@include('project-scroller.php'); ?>

<?php get_footer(); ?>