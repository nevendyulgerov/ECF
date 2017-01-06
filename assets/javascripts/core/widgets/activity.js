

// Global object KenobiSoft
// This is the only global used in the entire framework
var KenobiSoft = KenobiSoft || {};


// Define widgets object
KenobiSoft.widgets = KenobiSoft.widgets || {};


// Define activity widget
KenobiSoft.widgets.activity = KenobiSoft.widgets.activity || function($widget) {

    // define local vars
    var $ = jQuery;

    var init = function() {
        var chartDataPosts = $('.widget-activity-posts-pie-chart').data('chart-data');
        var chartDataVideos = $('.widget-activity-videos-pie-chart').data('chart-data');
        var chartDataPages = $('.widget-activity-pages-pie-chart').data('chart-data');

        var publishPosts = parseInt(chartDataPosts.publish);
        var draftPosts = parseInt(chartDataPosts.draft);
        var trashPosts = parseInt(chartDataPosts.trash);
        var totalPosts = publishPosts + draftPosts + trashPosts;

        var publishPages = parseInt(chartDataPages.publish);
        var draftPages = parseInt(chartDataPages.draft);
        var trashPages = parseInt(chartDataPages.trash);
        var totalPages = publishPages + draftPages + trashPages;

        var publishPostsPercentage = (100 * publishPosts) / totalPosts;
        var draftPostsPercentage = (100 * draftPosts) / totalPosts;
        var trashPostsPercentage = (100 * trashPosts) / totalPosts;

        var publishPagesPercentage = (100 * publishPages) / totalPages;
        var draftPagesPercentage = (100 * draftPages) / totalPages;
        var trashPagesPercentage = (100 * trashPages) / totalPages;

        // Posts data
        var dataPosts = {
            labels: [],
            series: []
        };

        if ( publishPosts > 0 ) {
            dataPosts.labels.push(publishPostsPercentage.toFixed(2)+'%');

            dataPosts.series.push({
                value: publishPosts,
                className: 'chart-publish-posts'
            });
        }

        if ( draftPosts > 0 ) {
            dataPosts.labels.push(draftPostsPercentage.toFixed(2)+'%');

            dataPosts.series.push({
                value: draftPosts,
                className: 'chart-draft-posts'
            });
        }

        if ( trashPosts > 0 ) {
            dataPosts.labels.push(trashPostsPercentage.toFixed(2)+'%');

            dataPosts.series.push({
                value: trashPosts,
                className: 'chart-trash-posts'
            });
        }


        // Pages data
        var dataPages = {
            labels: [],
            series: []
        };

        if ( publishPages > 0 ) {
            dataPages.labels.push(publishPagesPercentage.toFixed(2)+'%');

            dataPages.series.push({
                value: publishPages,
                className: 'chart-publish-posts'
            });
        }

        if ( draftPages > 0 ) {
            dataPages.labels.push(draftPagesPercentage.toFixed(2)+'%');

            dataPages.series.push({
                value: draftPages,
                className: 'chart-draft-posts'
            });
        }

        if ( trashPages > 0 ) {
            dataPages.labels.push(trashPagesPercentage.toFixed(2)+'%');

            dataPages.series.push({
                value: trashPages,
                className: 'chart-trash-posts'
            });
        }


        // initialize pie-charts
        new Chartist.Pie('.widget-activity-posts-pie-chart', dataPosts, {});
        new Chartist.Pie('.widget-activity-pages-pie-chart', dataPages, {});
    };

    init();
};