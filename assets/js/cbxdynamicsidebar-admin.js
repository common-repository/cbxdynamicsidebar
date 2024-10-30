(function ($) {
    'use strict';

    $(document).ready(function ($) {

        //console.log(cbxdynamicsidebar_js_vars);

        $(".chosen-select").chosen({disable_search_threshold: 10});

        /*if($('#cbxdynamicsidebarmetabox_fields_user_roles').length > 0){
           $('#cbxdynamicsidebarmetabox_fields_user_roles_sa').on('click', function (e) {
               e.preventDefault();

               $('#cbxdynamicsidebarmetabox_fields_user_roles').find('option').prop('selected', true);
               $('#cbxdynamicsidebarmetabox_fields_user_roles').trigger("chosen:updated");
           });

            $('#cbxdynamicsidebarmetabox_fields_user_roles_usa').on('click', function (e) {
                e.preventDefault();

                $('#cbxdynamicsidebarmetabox_fields_user_roles').find('option').prop('selected', false);
                $('#cbxdynamicsidebarmetabox_fields_user_roles').trigger("chosen:updated");
            });
        }*/


        //select all text on click of shortcode text
        $('.cbxsidebarshortcode').on("click", function () {

            var $this = $(this);
            var $flexi = parseInt($this.data('flexi'));

            var $class_text = ($flexi == 1) ? 'cbxsidebarshortcode-text cbxsidebarshortcode-text-flexi' : 'cbxsidebarshortcode-text"';

            var text = $this.text();
            var $input = $('<input class="' + $class_text + '" type="text">');
            $input.prop('value', text);
            $input.insertAfter($this);
            $input.focus();
            $input.select();

            try {
                document.execCommand("copy");
            } catch (err) {

            }
            $this.hide();
            $input.focusout(function () {
                $this.show();
                $input.remove();
            });
        });

        //add go to widgets link
        if(parseInt(cbxdynamicsidebar_js_vars.list_view) == 1){
			$( '<a href="'+cbxdynamicsidebar_js_vars.manage_widgets.url+'" class="page-title-action add-new-h2">'+cbxdynamicsidebar_js_vars.manage_widgets.title+'</a>' ).insertAfter( $('.page-title-action') );

        }
    });

})(jQuery);