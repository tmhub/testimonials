var Testimonials = Class.create();
Testimonials.prototype = {
    initialize: function(ajaxCallUrl, divToUpdate) {
        this.url = ajaxCallUrl;
        this.div = $$(divToUpdate)[0];
        this.currentPage = 1;
    },

    makeAjaxCall: function(event) {
        event.stop();
        if ($$('.more-button a')[0].hasClassName('disabled')) return;
        $$('.more-button a')[0].addClassName('disabled');
        ++this.currentPage;
        new Ajax.Request(this.url + 'page/' + this.currentPage, {
            onSuccess: function(transport) {
                var response = transport.responseText.evalJSON();
                this.div.insert(response.outputHtml);
                $$('.more-button a')[0].removeClassName('disabled');
            }.bind(this)
        });
    }
};

document.observe('dom:loaded', function() {
    // check if testimonials form rating box exist
    var testimonialRatingBox = $('testimonial-form-rating-box');
    if (testimonialRatingBox == undefined) {
        return;
    }
    // hide radiobuttons
    $$('.testimonialForm .ratings-table label').each(function (el){
       el.setStyle({'display': 'none'});
    });
    // show stars instead of radiobuttons
    testimonialRatingBox.setStyle({'display': ''});
    // listen star click on testimonial form
    testimonialRatingBox.observe('click', function(e) {
        var xPositionInDiv = e.pointerX() - this.cumulativeOffset().left;
        var singleStarWidth = this.getWidth() / 5;
        var stars = Math.floor( xPositionInDiv / singleStarWidth ) + 1;
        $('rating_' + stars).checked = 'checked';
        $('testimonial-form-rating').setStyle({'width' : (stars * 20) + '%'});
    });
});
