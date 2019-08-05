
/*SCRIP BINH THUONG*/
function showAlert() {
    alert('Hello THis is My First JavaScript')
}

/*SCRIPT MODAL CONFRIM*/
require([
        'jquery',
        'Magento_Ui/js/modal/confirm'
    ],
    function ($, confirmation) {
        $('#BT2').on('click', function (event) {
            confirmation({
                title: 'Magento Alert Modal',
                content: 'Hello This Is My First Nagento Alert Modal',
                actions: {
                    /**
                     * Callback always - called on all actions.
                     */
                    /*always: function () {alert('Tkank You')},*/ //Cancel or Ok alway excute function.

                    /**
                     * Callback confirm.
                     */
                    confirm: function () {
                        alert('Tkank You')
                    },

                    /**
                     * Callback cancel.
                     */
                    cancel: function () {
                    }
                }
            });
        });
    });

/*SCRIPT MODAL MODAL*/
require(
    [
        'jquery',
        'Magento_Ui/js/modal/modal'
    ],
    function (
        $,
        modal
    ) {
        var options = {
            type: 'popup',
            title: 'Login Modal',
            responsive: true,
            innerScroll: true,
            buttons: [{
                text: $.mage.__('Close'),
                click: function () {
                    this.closeModal();
                }
            }]
        };

        var popup = modal(options, $('#from'));
        $("#BT3").on('click', function () {
            $("#from").modal("openModal");
        });

    }
);

/*Youtube*/
require(
    [
        'jquery',
        'Magento_Ui/js/modal/modal'
    ],
    function(
        $,
        modal
    ) {
        var options = {
            type: 'popup',
            title:'YouTuBe',
            responsive: true,
            innerScroll: true,
            buttons: [{
                text: $.mage.__('Close'),
                click: function () {
                    this.closeModal();
                }
            }]
        };

        var popup = modal(options, $('#header-mpdal'));
        $("#BT4").on('click',function(){
            $("#header-mpdal").modal("openModal");
        });

    }
);

$('.message a').click(function () {
    $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
});
