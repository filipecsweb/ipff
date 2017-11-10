(function ($) {

    var l10n = {
        user_denied_app: localized_admin_ipff.user_denied_app
    };

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
     * Handles Instagram User data.
     */
    // Init.
    (function () {
        var query_string = window.location.search;

        var error_reason = query_string.match(/error_reason=([^&]+)/);

        if (error_reason !== null) {
            var notice = get_notice(l10n.user_denied_app, 'error');

            $('.notice-wrapper', '.wrap').html(notice);
        } else {
            var parent_element = query_string.match(/parent_element=([^&]+)/);

            if (parent_element === null) {
                return false;
            }

            var token = query_string.match(/token=([^&]+)/);

            if (token === null) {
                return false;
            }

            var username = query_string.match(/username=([^&]+)/);

            if (username === null) {
                return false;
            }

            set_user_data(
                {
                    parent_element: parent_element[1],
                    token: token[1],
                    username: username[1]
                }
            );
        }

    })();

    function set_user_data(data) {
        var parent = $("#" + data.parent_element);

        $('[name*="username"]', parent).val(data.username);
        $('[name*="token"]', parent).val(data.token);
    }

    function get_notice(html, type) {
        var notice = "<div class='notice notice-" + type + " is-dismissible'>";
        notice += "<p>" + html + "</p>";
        notice += "</div";

        return notice
    }

})(jQuery);
