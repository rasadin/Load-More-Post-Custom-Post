;(function($) {
   /**
    * Load More services
    * @author 
    * @since 1.0.0
    */
   var loadmorePortfolios = function() {

    var loadMoreBtn = '.js-load-more-portfolio';
    var perPage = parseInt(theme_localizer.posts_per_page, 10);
    var offset = perPage;

    $(document.body).on('click', loadMoreBtn, function(e) {
        var _self = $(this);
        e.preventDefault();

        var appnedTo = $('.js-portfolio-appender');
        var template = wp.template('load-portfolios');

        var spinner = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';

        $.ajax({
            url: theme_localizer.ajax_url,
            type: 'post',
            data: {
                action: 'loadmore_portfolios', // [N.B] public\Modules\Portfolio\Portfolio.php
                per_page: perPage,
                offset: offset,
            },
            beforeSend: function() {
                _self.html('').append(spinner);
            },
            success: function(res) {
                // Parsing JSON Data
                var data  = JSON.parse(res);
                // Increment Offset
                offset += parseInt( perPage, 10 );
                
                // Append Data
                appnedTo.append(template(data));

                // Remove Loader
                _self.html('Load More');

                // Hiding Load More Button If No More Posts.
                if( data.totalPortfolios <= offset || data.totalPortfolios == perPage  ) {
                    _self.hide();
                }

            }
        });

    })

}
loadmorePortfolios();
    
})(jQuery);
