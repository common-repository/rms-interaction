jQuery(function ($) {
    function getCookie(rms_referral) {
        var name = rms_referral + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    $("#rms_login").submit(function (event) {
        var me = this;
        event.preventDefault();
        var datalogin = $('#rms_login').serializeObject();
        $(this).find('input').each(function (i, e) {
            $(e).attr('disabled', 'disabled');
        });
        $('#login_btn').val('Đang đăng nhập...');

        jQuery.ajax({
            url: ajax_url,
            dataType: 'json',
            method: "POST",
            data:
                {
                    action: 'rms_login',
                    data: datalogin
                },
            success: function (result) {
                if (result) {
                    if (result.success) {
                        location.reload(true);
                    }
                    else {
                        alert(result.message);
                    }
                }
                else
                    alert('Kết nối máy chủ thất bại, Vui lòng thử lại!');

                $(me).find('input').each(function (i, e) {
                    $(e).removeAttr('disabled');
                });
                $('#login_btn').val('Đăng nhập');
            }
        });
    });

    $("#rms_register").submit(function (event) {
        var formrm = this;
        event.preventDefault();
        var dataregister = $("#rms_register").serializeObject();
        $(formrm).find('input').each(function (i, e) {
            $(e).attr('disabled', 'disabled');
        });
        $(formrm).attr('disabled', 'disabled');
        $(formrm).find(".btn-pass-regis").val('Đang đăng ký...');

        jQuery.ajax({
            url: ajax_url,
            dataType: "json",
            method: "POST",
            data:
                {
                    action: 'rms_register',
                    data: dataregister
                },
            success: function (result) {
                if (result) {
                    if (result.success) {
                        event.preventDefault();
                        if ($(formrm).find(".notification_popup").html().trim()) {
                            var popup = '<div class="background-popuptks" style="background: rgba(0, 0, 0, 0.14);">' +
                                '<div class="content-notification">' +
                                '<div class="close-popup"><span class="pull-right btn-close">X</span></div>' +
                                '<div class="content-iframe" style="padding: 25px"><div class="content-success">' + $(formrm).find(".notification_popup").html() + '</div>' +
                                '</div></div></div>';
                            $("body").append(popup);
                            $(".background-popuptks").click(function (event) {
                                var target = $(event.target);
                                if (!target.is('.content-notification') && !target.is('.content-notification *')) {
                                    $(".background-popuptks").remove();
                                }

                            });
                            $(".btn-close").click(function (event) {
                                $(".background-popuptks").hide();
                                location.reload(true);
                            })
                        }
                        else {
                            alert("Đăng ký thành công, vui lòng kiểm tra email để xác thực!")
                            location.reload(true);
                        }
                    }
                    else {
                        alert(result.message);
                    }
                }
                else
                    alert('Kết nối máy chủ thất bại, Vui lòng thử lại!');
                $(formrm).find('input').each(function (i, e) {
                    $(e).removeAttr('disabled');
                });
                $(formrm).find('.btn-pass-regis').val('Đăng ký');
            }
        });
    });

    $("#popup-polyci,#popup-ifamre").on("click", function () {
        $("body").append("<div class='background-polyci'>"
            + "<iframe src='<?php echo get_option('rms_option_url').'/term';?>'>"
            + "<p>Your browser does not support iframes.</p>"
            + "</iframe>"
            + "</div>"
        );
        $("html").addClass("overflow-hidden");
        $(".background-polyci").on("click", function () {
            $("html").removeClass("overflow-hidden");
            $(".background-polyci").remove();
        });
    });

    $('#logout-rms').click(function () {
        jQuery.ajax({
            url: ajax_url,
            dataType: "json",
            method: "POST",
            data:
                {
                    action: 'rms_logout',
                },
            success: function (result) {
                window.location.href = window.location.pathname;
            }
        });
    });

    $(document).on('keyup', '#nickname', function (event) {
        var regex = /^[a-zA-Z0-9.\-_$@*!]{5,30}$/;
        var text = $('#nickname').val();
        var p = regex.test(text) ? 'true' : 'false';
        if (p == 'false') {
            $("#nickname").css("borderColor", "red");
            $('#span-nickname').show();
        } else {
            $("#nickname").css("borderColor", "green");
            $('#span-nickname').hide();

        }
    });

    $(document).on('blur', '#confirmed_email', function (event) {
        $(this).css("borderColor", $(this).val() == $('#email_rm').val() ? "green" : "red");
    });

    function get_browser() {
        var nAgt = navigator.userAgent;
        var browserName = navigator.appName;
        var nameOffset, verOffset, ix;

        if ((verOffset = nAgt.indexOf("Opera")) != -1) {
            browserName = "Opera";
        }
        else if ((verOffset = nAgt.indexOf("MSIE")) != -1) {
            browserName = "Microsoft Internet Explorer";
        }
        else if ((verOffset = nAgt.indexOf("Chrome")) != -1) {
            browserName = "Chrome";
        }
        else if ((verOffset = nAgt.indexOf("Safari")) != -1) {
            browserName = "Safari";
        }
        else if ((verOffset = nAgt.indexOf("Firefox")) != -1) {
            browserName = "Firefox";
        }
        else if ((nameOffset = nAgt.lastIndexOf(' ') + 1) <
            (verOffset = nAgt.lastIndexOf('/'))) {
            browserName = nAgt.substring(nameOffset, verOffset);
            if (browserName.toLowerCase() == browserName.toUpperCase()) {
                browserName = navigator.appName;
            }
        }
        return browserName;
    }

    $(document).ready(function () {
        if (document.cookie.indexOf("rms_referral") >= 0) {
            $('body').find('a').each(function (index) {
                var rms = getCookie("rms_referral");
                var key = $('#rms_key_aff').val();
                var a_href = $(this).attr('href');
                a_href = a_href ? a_href : '';
                var check_href = a_href.indexOf(document.domain);
                var today = new Date();
                var time = today.getTime();
                if (!/\?key.val()=/g.test(a_href) && (check_href < 0 && a_href.indexOf('/') !== 0 && a_href.indexOf('#') !== 0)) {
                    var sym = (/\?/g.test(a_href) ? '&' : '?');
                    rms = 'sharing=' + time + '&' + key + '=' + rms + '&brower=' + get_browser();
                    $(this).attr('href', a_href + sym + rms);
                }
            });
        }
    });
});