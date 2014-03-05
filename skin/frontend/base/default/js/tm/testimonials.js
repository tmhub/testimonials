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