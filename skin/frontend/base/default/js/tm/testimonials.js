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

(function (exports){

    var widgetContentSelector = '.block-testimonials .content .content-wrapper',
        curTestimonial = 0,
        showMoreActive = false,
        changeInterval,
        contentHeight;

    var WidgetList = Class.create();
    WidgetList.prototype = {
        initialize: function (element) {
            if (!element) {
                return;
            }
            self = this;
            ['num-testimonials','view-time','anim-duration'].each(
                function (v) {
                    self[v.camelize()] = element.readAttribute('data-'+v);
                });
        }
    }

    exports.testimonialWL = new WidgetList();

})(this);

(function (exports){

    var TestimonialForm = Class.create(VarienForm, {

        initRatingStars: function(){
            var ratingRadiosSelector = '.testimonialForm .ratings-table label',
                ratingBox = $('testimonial-form-rating-box');
            // check if testimonials form rating box exist
            if (!ratingBox) {
                return;
            }
            // hide radiobuttons
            $$(ratingRadiosSelector).each(function (el){
               el.setStyle({'display': 'none'});
            });
            // show stars instead of radiobuttons
            ratingBox.setStyle({'display': ''});
            // listen star click on testimonial form
            ratingBox.observe('click', function(event) {
                var xPosInDiv = event.pointerX() - this.cumulativeOffset().left;
                var starWidth = this.getWidth() / 5;
                var n = Math.floor( xPosInDiv / starWidth ) + 1;
                $('rating_' + n).checked = 'checked';
                $('testimonial-form-rating').setStyle({'width' : (n*20) + '%'});
            });
        }

    });

    exports.testimonialForm = new TestimonialForm();

})(this);

document.observe('dom:loaded', function() {
    testimonialForm.initialize('testimonialForm', true);
    if (testimonialForm.form) {
        testimonialForm.initRatingStars();
    }
    testimonialWL.initialize($('testimonialsList'));
});
