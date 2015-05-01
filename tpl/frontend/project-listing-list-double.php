<div class="project-slider-double-wrapper">
    <a href="#prev-image" class="icon-arrow arrow-prev" title="Previous"></a>
    <div class="project-slider-double project-manager-scroller-content">
        <?php $projects_count = count($projects); ?>
        <?php for ($i = 0; $i < $projects_count; $i += 2) { /* We are incrementing each loop by 2 because we include two projects in each iteration */ ?>
            <?php if (array_key_exists($i+1,$projects)) { ?>
            <div class="slide">
                <div class="project-card">
                    <a href="<?php echo get_permalink($projects[$i]->ID); ?>"><img src="<?php echo $projects[$i]->meta['project-manager-images'][0]; ?>"></a>
                </div>
                <div class="project-card">
                    <a href="<?php echo get_permalink($projects[$i+1]->ID); ?>"><img src="<?php echo $projects[$i+1]->meta['project-manager-images'][0]; ?>"></a>
                </div>
            </div>
            <?php } ?>
        <?php } ?>    
    </div>
    <a href="#next-image" class="icon-arrow arrow-next" title="Next"></a>
</div>
