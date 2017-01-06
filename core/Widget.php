<?php

namespace ECF;

/**
 * Class Widget
 * Controls the widgets
 * @package ECF
 */
class Widget {


    /**
     * Construct
     */
    public function __construct() {}


    /**
     * Create widget
     * @param string $widget
     */
    public static function createWidget($widget) {

        switch ($widget) {
            case 'activity':
                self::_createWidgetActivity();
                break;
            case 'statistics':
                self::_createWidgetStatistics();
                break;
            case 'plugins':
                self::_createWidgetPlugins();
                break;
            default:
                break;
        }
    }


    /**
     * Create widget activity
     */
    protected static function _createWidgetActivity() {
        // Get posts data
        $posts_data = wp_count_posts('post');

        // Get pages data
        $pages_data = wp_count_posts('page');
        ?>

        <div class="custom-widget" data-widget="activity">

            <!-- block-section -->
            <div class="view-block-section">
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        <p class="view-block-section-heading"><?php echo __('Pages'); ?></p>
                        <p><span class="view-block-label"><?php echo __('Published pages:'); ?></span> <span class="view-block-count"><?php echo $pages_data->publish; ?></span> <span class="publish-posts"></span></p>
                        <p><span class="view-block-label"><?php echo __('Draft pages:'); ?></span> <span class="view-block-count"><?php echo $pages_data->draft; ?></span> <span class="draft-posts"></span></p>
                        <p><span class="view-block-label"><?php echo __('Trashed pages:'); ?></span> <span class="view-block-count"><?php echo $pages_data->trash; ?></span> <span class="trash-posts"></span></p>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="widget-activity-pages-pie-chart" data-chart-data="<?php echo stripcslashes(htmlentities(json_encode($pages_data))); ?>"></div>
                    </div>
                </div>
            </div>
            <!-- /block-section -->

            <!-- block-section -->
            <div class="view-block-section">
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        <p class="view-block-section-heading"><?php echo __('Posts'); ?></p>
                        <p><span class="view-block-label"><?php echo __('Published posts:'); ?></span> <span class="view-block-count"><?php echo $posts_data->publish; ?></span> <span class="publish-posts"></span></p>
                        <p><span class="view-block-label"><?php echo __('Draft posts:'); ?></span> <span class="view-block-count"><?php echo $posts_data->draft; ?></span> <span class="draft-posts"></span></p>
                        <p><span class="view-block-label"><?php echo __('Trashed posts:'); ?></span> <span class="view-block-count"><?php echo $posts_data->trash; ?></span> <span class="trash-posts"></span></p>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="widget-activity-posts-pie-chart" data-chart-data="<?php echo stripcslashes(htmlentities(json_encode($posts_data))); ?>"></div>
                    </div>
                </div>
            </div>
            <!-- /block-section -->

        </div>
        <?php
    }


    /**
     * Create widget plugins
     */
    protected static function _createWidgetPlugins() {
        // Get plugins data
        $plugins_data = get_plugins();

        // Get active plugins
        $active_plugins = get_option('active_plugins');
        ?>

        <!-- content -->
        <div class="custom-widget" data-widget="plugins">

            <!-- block-section -->
            <div class="view-block-section">
                <div class="widget-box-medium">
                    <?php if ( count($plugins_data) > 0 ) : ?>
                        <p class="view-block-section-heading"><span class="view-block-label"><?php echo __('General:'); ?></span></p>

                        <p><span class="view-block-label"><?php echo __('Installed plugins:'); ?></span> <span class="view-block-count"><?php echo count($plugins_data); ?></span> </p>

                        <p><span class="view-block-label"><?php echo __('Active plugins:'); ?></span> <span class="view-block-count"><?php echo count($active_plugins); ?></span> </p>

                        <p><span class="view-block-label"><?php echo __('Disabled plugins:'); ?></span> <span class="view-block-count"><?php echo count($plugins_data) - count($active_plugins); ?></span> </p>

                    <?php endif; ?>
                </div>
            </div>
            <!-- /block-section -->

            <!-- block-section -->
            <div class="view-block-section">
                <div class="widget-box-medium">
                    <p class="view-block-section-heading"><span class="view-block-label"><?php echo __('Installed plugins:'); ?></span></p>
                    <?php foreach($plugins_data as $name=>$p) : ?>

                        <?php if ( in_array($name, $active_plugins) ) : ?>
                            <span class="view-block-label-large" title="<?php echo __('Active plugin'); ?>"><?php echo $p['Name']; ?></span> <span class="active-plugin" title="<?php echo __('Active plugin'); ?>"></span><br/>
                        <?php else: ?>
                            <span class="view-block-label-large" title="<?php echo __('Disabled plugin'); ?>"><?php echo $p['Name']; ?></span> <span class="disabled-plugin" title="<?php echo __('Disabled plugin'); ?>"></span><br/>
                        <?php endif; ?>

                    <?php endforeach; ?>
                </div>
            </div>
            <!-- /block-section -->

        </div>
        <!-- /content -->
        <?php
    }


    /**
     * Create widget statistics
     */
    protected static function _createWidgetStatistics() {
        // Get users data
        $users_data = count_users();

        // Get comments data
        $comments_data = wp_count_comments();
        ?>

        <!-- content -->
        <div class="custom-widget" data-widget="statistics">

            <!-- block-section -->
            <div class="view-block-section">
                <p class="view-block-section-heading"><?php echo __('Users'); ?></p>
                <div class="row">
                    <div class="col-sm-12 col-md-4">

                        <?php if ( isset($users_data['avail_roles']['administrator']) ) : ?>
                            <p><span class="view-block-label"><?php echo __('Administrators:'); ?></span> <span class="view-block-count"><?php echo $users_data['avail_roles']['administrator']; ?></span> </p>
                        <?php endif; ?>

                        <?php if ( isset($users_data['avail_roles']['subscriber']) ) : ?>
                            <p><span class="view-block-label"><?php echo __('Subscribers:'); ?></span> <span class="view-block-count"><?php echo $users_data['avail_roles']['subscriber']; ?></span> </p>
                        <?php endif; ?>

                        <?php if ( isset($users_data['avail_roles']['contributor']) ) : ?>
                            <p><span class="view-block-label"><?php echo __('Contributors:'); ?></span> <span class="view-block-count"><?php echo $users_data['avail_roles']['contributor']; ?></span> </p>
                        <?php endif; ?>

                        <?php if ( isset($users_data['avail_roles']['author']) ) : ?>
                            <p><span class="view-block-label"><?php echo __('Authors:'); ?></span> <span class="view-block-count"><?php echo $users_data['avail_roles']['author']; ?></span> </p>
                        <?php endif; ?>

                        <?php if ( isset($users_data['avail_roles']['editor']) ) : ?>
                            <p><span class="view-block-label"><?php echo __('Editors:'); ?></span> <span class="view-block-count"><?php echo $users_data['avail_roles']['editor']; ?></span> </p>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
            <!-- /block-section -->

            <!-- block-section -->
            <div class="view-block-section">
                <p class="view-block-section-heading"><?php echo __('Comments'); ?></p>
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        <p><span class="view-block-label"><?php echo __('Total number:'); ?></span> <span class="view-block-count"><?php echo $comments_data->total_comments; ?></span> </p>
                    </div>
                </div>
            </div>
            <!-- /block-section -->

        </div>
        <!-- /content -->
        <?php
    }
}