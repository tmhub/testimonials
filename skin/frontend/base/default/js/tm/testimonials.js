;(function (exports){
    // testimonials widget list
    var widgetContentSelector = '.block-testimonials .content .content-wrapper',
        config = {},
        itemPrefix = 'testimonial_',
        curTestimonial = 0,
        showMoreActive = false,
        contentHeight,
        changeInterval;

    function showMore(e) {
        e.stop();
        showMoreActive = true;
        this.hide();
        this.up().down('.read-less').show();
        this.up().down('.content-wrapper').setStyle({height: 'auto'});
    }

    function showLess(e) {
        e.stop();
        showMoreActive = false;
        this.hide();
        this.up().down('.read-more').show();
        this.up().down('.content-wrapper').setStyle({height: contentHeight});
    }

    function startChangeTimer() {
        if (!showMoreActive) {
            changeInterval = setInterval(nextTestimonial, config.viewTime);
        }
    }

    function nextTestimonial() {
        if (config.numTestimonials < 2) {
            return;
        }
        if ($(itemPrefix + '0').down('.read-more')) {
            $(itemPrefix + curTestimonial).down('.read-more').stopObserving();
            $(itemPrefix + curTestimonial).down('.read-less').stopObserving();
        }
        Effect.Fade(itemPrefix + curTestimonial, {
            duration: config.animDuration / 1000
        });

        ++curTestimonial;
        if (curTestimonial >= config.numTestimonials) {
            curTestimonial = 0;
        }

        setTimeout(function() {
            Effect.Appear(itemPrefix + curTestimonial, {
                duration: config.animDuration / 1000
            });
            if ($(itemPrefix + '0').down('.read-more')) {
                var elem = $(itemPrefix + curTestimonial);
                elem.down('.read-more').observe('click', showMore);
                elem.down('.read-less').observe('click', showLess);
            }
        }, config.animDuration);
    }

    var WidgetList = Class.create();
    WidgetList.prototype = {
        initialize: function (element) {
            if (!element) {
                return;
            }
            contentHeight = $$(widgetContentSelector)[0].getStyle('height');
            config = JSON.parse(element.readAttribute('data-widget-config'));
            // set min height on testimonial container so it does not jump
            var testimonialContainer = element.down('.testimonial-container');
            if (testimonialContainer) {
                testimonialContainer.setStyle({
                    minHeight: testimonialContainer.getHeight()+'px'
                });
            }
            element.observe('mouseenter', function() {
                if (!showMoreActive) clearInterval(changeInterval);
            });
            element.observe('mouseleave', startChangeTimer);
            var elem = $(itemPrefix + '0');
            elem.down('.read-more').observe('click', showMore);
            elem.down('.read-less').observe('click', showLess);
            startChangeTimer();
        },

        next: function() {
            nextTestimonial();
        }
    };

    // testimonials - post new testimonail form
    var TestimonialForm = Class.create(VarienForm, {

        initialize: function($super, formId, firstFieldFocus){
            $super(formId, firstFieldFocus);
            if (this.form) {
                this.initRatingStars();
            }
        },

        initRatingStars: function(){
            var ratingRadiosSelector = '.testimonialForm .ratings-table label',
                ratingBox = $('testimonial-form-rating-box');
            if (!ratingBox) {
                return;
            }
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
                $('rating_hidden').setValue(n);
                $('rating_' + n).checked = 'checked';
                $('testimonial-form-rating').setStyle({'width' : (n*20) + '%'});
            });
        }

    });

    // testimonails page
    var Testimonials = Class.create();
    Testimonials.prototype = {
        initialize: function(divToUpdate) {
            if (!divToUpdate) {
                return;
            }
            this.url = divToUpdate.readAttribute('data-ajax-url');
            this.div = divToUpdate;
            this.currentPage = 1;
        },

        makeAjaxCall: function(event) {
            event.stop();
            if ($$('.more-button button')[0].hasClassName('disabled')) return;
            $$('.more-button button')[0].addClassName('disabled');
            ++this.currentPage;
            new Ajax.Request(this.url + 'page/' + this.currentPage, {
                onSuccess: function(transport) {
                    var response = transport.responseText.evalJSON();
                    this.div.insert(response.outputHtml);
                    $$('.more-button button')[0].removeClassName('disabled');
                }.bind(this)
            });
        }
    };

    var testimonialObject = {};
    testimonialObject.widgetList = new WidgetList();
    testimonialObject.form = new TestimonialForm();
    testimonialObject.list = new Testimonials();

    exports.testimonial = testimonialObject;

})(this);

document.observe('dom:loaded', function() {
    testimonial.form.initialize('testimonialForm', true);
    testimonial.widgetList.initialize($('testimonialsList'));
    var listContainer = $$('.testimonials-list .testimonials');
    if (!listContainer.length) { return; }
    testimonial.list.initialize(listContainer[0]);
    $('testimonials-view-more-button').observe(
        'click',
        testimonial.list.makeAjaxCall.bind(testimonial.list)
    );
});
