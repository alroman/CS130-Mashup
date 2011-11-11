$(document).ready(function() {
   initTags();
   $('.tag').click(function(){
      var $div = $(this);
      $div.toggleClass('active');
      var checkbox = $div.find('input');
      if (checkbox.is(':checked')) {
         checkbox.prop('checked', false);
         // console.log($.trim($div.text()));
      } else {
         checkbox.prop('checked', true);
         // console.log($.trim($div.text()));
      }
      return false;
   });

   // Ajax calls for using updating the events
   function filterEvents ($obj, url, data) {
      $.post('/home/filter');
   }

   function initTags() {
      var tags = $('.tag input');
      tags.each(function (tag) {
         $(tag).parent().removeClass('active');
         $(tag).removeAttr('checked');
      });
   }
});
