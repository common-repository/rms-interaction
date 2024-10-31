"use strict"

var RmsShare={};
(
    function (e,$){

        e.init = function () {
            e.show_share_referral = $('#show_share_referral');
            e.rms_share_mode = $('#rms_share_mode');
            e.close_share = $('.close_share');

        };

        e.bind_events = function () {
            e.show_share_referral.click(function () {
                e.rms_share_mode.fadeToggle();
                $("html").addClass("hidden-scroll");
                $("header").addClass("hidden-scroll");
            });

            e.close_share.click(function () {
                e.rms_share_mode.fadeOut();
                $("html").removeClass("hidden-scroll");
                $("header").removeClass("hidden-scroll");
            });

            e.rms_share_mode.click(function (event) {
                var target = $(event.target);
                if (!target.is('input') && !target.is('i') && !target.is('article') && !target.is('article *')) {
                    e.rms_share_mode.fadeOut();
                    $("html").removeClass("hidden-scroll");
                    $("header").removeClass("hidden-scroll");
                }
            });
        };

        $(function(){
            RmsShare.init();
            if(e.show_share_referral)
                RmsShare.bind_events();
        })
    }
)(RmsShare,jQuery);
