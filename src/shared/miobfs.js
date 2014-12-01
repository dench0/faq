(function($) {
  $(document).ready(function () {
    $(document).on('contextmenu', function(e) {
      if ($(e.target).is(".mi-download-link") || $(e.target).parents(".mi-download-link").length != 0) {
        return false;
      } else {
        return true;
      }
    });
    $('.mi-download-link').click(function () {
      window.location.href = '/engine/classes/moneyinst/mi_request.php?' + 'sid=' + $(this).attr('download_sid') +
      '&url=' + $(this).attr('download_url') + '&name=' + $(this).attr('download_name') +
      '&type=' + $(this).attr('download_type') + '&href='+encodeURIComponent($(this).attr('href'));
      return false;
    });
  });
})(jQuery);