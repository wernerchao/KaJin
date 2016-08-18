<div class="grid_6 alpha">
    <div class="footer_columns first">
        <?php if (is_active_sidebar('first-footer-widget-area')) : ?>
            <?php dynamic_sidebar('first-footer-widget-area'); ?>
        <?php else : ?>
            <h4><?php _e('Footer Widgets', 'one-page'); ?></h4>
            <p><?php _e('Footer is widgetized. To setup the footer, drag the required Widgets in Appearance -> Widgets Tab in the First, Second, Third and Fourth Footer Widget Areas.', 'one-page'); ?></p>
        <?php endif; ?>
    </div>
</div>
<div class="grid_6">
    <div class="footer_columns second">
        <?php if (is_active_sidebar('second-footer-widget-area')) : ?>
            <?php dynamic_sidebar('second-footer-widget-area'); ?>
        <?php else : ?> 
            <h4><?php _e('Latest Posts', 'one-page'); ?></h4>
            <ul>
                <li><a href="#"><?php _e('Entertainment', 'one-page'); ?></a></li>
                <li><a href="#"><?php _e('Following problems', 'one-page'); ?></a></li>
                <li><a href="#"><?php _e('FAQ', 'one-page'); ?></a></li>
                <li><a href="#"><?php _e('Music And Sports', 'one-page'); ?></a></li>
            </ul>
        <?php endif; ?> 
    </div>
</div>
<div class="grid_6">
    <div class="footer_columns third">
        <?php if (is_active_sidebar('third-footer-widget-area')) : ?>
            <?php dynamic_sidebar('third-footer-widget-area'); ?>
        <?php else : ?>
            <h4><?php _e('Search Anything', 'one-page'); ?></h4>
            <?php _e('Address: Magnet Brains 10 No. Arera Colony, Bhopal India<br/>
            Contact No : +91-9926465653<br/>     
            Email : support@inkthemes.com', 'one-page'); ?>
        <?php endif; ?>
    </div>
</div>
<div class="grid_6 omega">
    <div class="footer_columns last">
        <?php if (is_active_sidebar('fourth-footer-widget-area')) : ?>
            <?php dynamic_sidebar('fourth-footer-widget-area'); ?>
        <?php else : ?>
            <h4><?php _e('Use Of Widgets', 'one-page'); ?></h4>
            <p><?php _e('You can easily drag and drop the widgets here to display under the footer. You just need to go to your dashboard and there you can choose the appearance option and then widgets.', 'one-page'); ?></p>
        <?php endif; ?>
    </div>
</div>
<!-- ***********************Footer Page Ends************************* -->

