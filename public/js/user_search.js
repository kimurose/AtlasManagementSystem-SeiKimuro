$(function () {
  $('.search_conditions').click(function () {
    $('.search_conditions_inner').slideToggle();
  });

  $('.subject_edit_btn').click(function () {
    $('.subject_inner').slideToggle();
  });
});

$(document).ready(function() {
  $('.search_conditions').on('click', function() {
    $(this).toggleClass('open');
  });
});