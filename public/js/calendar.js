// $(function confirmDelete(reservetionId) {
//   var modal =new bootstrap.Modal(document.getElementById('comfimModal'));
//   modal.show();

//   // 確認ボタンのイベントリスナーを追加
//   document.getElementById('confirmDeleteButton').onclick = function() {
//     // フォームを作成して送信
//     var form = document.createElement('form');
//     form.method = 'POST';
//     form.action = '{{ route("delete.reservation") }}';

//     var input = document.createElement('input');
//     input.type = 'hidden';
//     input.name = 'delete_date';
//     input.value = reservationId;

//     form.appendChild(input);
//     document.body.appendChild(form);
//     form.submit();
//   }

// });

// function confirmDelete(button) {
//   if (confirm("この予約を削除しますか？")) {
//       // フォームを送信
//       document.getElementById('deleteParts').submit();
//   }
// }

// function setDeleteValue(reserveValue) {
//   document.getElementById('deleteDateValue').value = reserveValue;
//   $('.js-modal').fadeIn();
// }

// $(document).ready(function() {
//   $('.delete-button').on('click', function() {
//     const reserveValue =$(this).data('reserve-value');
//     setDeleteValue(reserveValue);
//   });

//   $('.js-modal-close').on('click', function() {
//     $('.js-modal').fadeOut();
//   });

//   function setDeleteValue(reserveValue) {
//     document.getElementById('deleteDateValue');
//     $('.js-modal').fadeIn();
//   }
// })

document.addEventListener('turbolinks:load', () => {
  $('#deleteModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var reserveId = button.data('reserve-id');
    var reserveDate = button.data('reserve-date');
    var reservePart = button.data('reserve-part');
    var reservePartName = button.data('reserve-part-name');
    setDeleteValue(reserveId, reserveDate, reservePart, reservePartName);
    // modal.find('.modal-body');
  });
});
function setDeleteValue(reserveId, reserveDate, reservePart, reservePartName) {
  console.log('reserveId:', reserveId);
  console.log('reserveDate:', reserveDate);
  console.log('reservePart:', reservePart);
  console.log('reservePartName:', reservePartName);

  

  document.getElementById('deleteDateValue').value = reserveId;
  document.getElementById('deletePartValue').value = reservePart;
  document.getElementById('reservationDate').innerText = reserveDate;
  document.getElementById('reservationTime').innerText = reservePartName; // ここが表示される部分
  $('#deleteModal').modal('show');
}
