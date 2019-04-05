(function ($) {

    var elements = {
            instagram_users_wrapper: $('.instagram-users-wrapper')
        },
        l10n = {
            denied: localized_admin_ipff.denied,
            reauthorized: localized_admin_ipff.reauthorized,
            authorized: localized_admin_ipff.authorized,
            ipff_settings: localized_admin_ipff.ipff_settings
        },
        ig_response = {};

    /**
     * Handles nav tabs UI.
     */
    $('.wrap').on('click', 'a.nav-tab', function (e) {

        e.preventDefault();

        $('a.nav-tab', '.wrap').removeClass('nav-tab-active');

        $(this).addClass('nav-tab-active');

        $('.tabs-panel', '.wrap').removeClass('tabs-panel-active');

        $($(this).attr('href')).addClass('tabs-panel-active')

    });

    /**
     * Handles Instagram authorization response.
     */
    (function () {

        var hash = window.location.hash;

        if (hash === "") {
            return false;
        }

        $.each(hash.split('#'), function (i, value) {
            if (value.length === 0) {
                return;
            }

            var pieces = value.match(/([^=]+)=(.+)/);

            //ig_response[token] = 2183gsdagsd81yhjsgd.
            ig_response[pieces[1]] = pieces[2];
        });

        if ("error_reason" in ig_response) {
            set_notice({
                type: "error",
                msg: l10n.denied
            });

            return false;
        }

        if (!("username") in ig_response) {
            return false;
        }

        if (instagram_user_already_exits(ig_response) === true) {
            set_notice({
                type: "error",
                msg: l10n.reauthorized.replace("%s", ig_response.username)
            });

            return false;
        }

        set_notice({
            type: "info",
            msg: l10n.authorized.replace("%s", ig_response.username)
        });

        set_instagram_user(ig_response);

    })();

    function instagram_user_already_exits(args) {

        var answer = false;

        $(".instagram-user input[name*='username']", elements.instagram_users_wrapper).each(function () {

            if ($(this).val() === args.username) {
                answer = true;

                return false;
            }

        });

        return answer;

    }

    function set_instagram_user(args) {

        console.log(args);

        var ipff_settings = l10n.ipff_settings;

        if (ipff_settings.length === 0) {
            $('.instagram-user.hidden', elements.instagram_users_wrapper).removeClass('hidden');
        } else {
            var user_count = parseInt($('.instagram-user:last', elements.instagram_users_wrapper).data('user-count'));
            var next_user_count = user_count + 1;
            var subject = new RegExp("user-" + user_count, "g");

            $('.instagram-user:last', elements.instagram_users_wrapper)
                .clone(true)
                .html(function (i, oldHTML) {
                    return oldHTML.replace(subject, "user-" + next_user_count);
                })
                .appendTo(elements.instagram_users_wrapper);
        }

        $('.instagram-user:last [name*="username"]', elements.instagram_users_wrapper).val(args.username);
        $('.instagram-user:last [name*="id"]', elements.instagram_users_wrapper).val(args.id);
        $('.instagram-user:last [name*="profile_picture"]', elements.instagram_users_wrapper).val(args.profile_picture);
        $('.instagram-user:last .profile-picture', elements.instagram_users_wrapper).attr('src', args.profile_picture);
        $('.instagram-user:last [name*="token"]', elements.instagram_users_wrapper).val(args.token);
        $(".btn-auth-user-wrapper").addClass("hidden");

    }

    function set_notice(args) {
        var html = "<div class='notice notice-" + args.type + " is-dismissible'>" +
            "<p>" + args.msg + "</p>" +
            "</div>";

        $(".notice-wrapper", ".wrap").html(html);
    }

})(jQuery);
