<?php
namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

class CalendarView{

  private $carbon;
  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  public function getTitle(){
    return $this->carbon->format('Y年n月');
  }

  function render(){
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th class="border">月</th>';
    $html[] = '<th class="border">火</th>';
    $html[] = '<th class="border">水</th>';
    $html[] = '<th class="border">木</th>';
    $html[] = '<th class="border">金</th>';
    $html[] = '<th class="border day-sat">土</th>';
    $html[] = '<th class="border day-sun">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks();
    foreach($weeks as $week){
      $html[] = '<tr class="'.$week->getClassName().'">';

      $days = $week->getDays();
      foreach($days as $day){
        $startDay = $this->carbon->copy()->format("Y-m-01");
        $toDay = $this->carbon->copy()->format("Y-m-d");

        if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
          // 過去日
          $html[] = '<td class="calendar-td border past-day '.$day->getClassName().'">';
        } else {
            // 未来の日付に対応
            $html[] = '<td class="calendar-td border '.$day->getClassName().'">';
        }
        
        $html[] = $day->render();
        
        if (in_array($day->everyDay(), $day->authReserveDay())) {  //予約がある日
            $reservePart = $day->authReserveDate($day->everyDay())->first()->setting_part;
            $reserveId = $day->authReserveDate($day->everyDay())->first()->setting_reserve;
            $reserveDate = $day->everyDay();
            
            if ($reservePart == 1) {
                $reservePartName = "リモ1部";
            } else if ($reservePart == 2) {
                $reservePartName = "リモ2部";
            } else if ($reservePart == 3) {
                $reservePartName = "リモ3部";
            }
        
            if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {  //今日より前の日にち→つまり過去日
                // $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px">'
                // . ($reservePart == 1 ? 'リモ1部参加' 
                // : ($reservePart == 2 ? 'リモ2部参加' 
                // : 'リモ3部参加')) 
                // . '</p>';
                // 過去の日付で予約があった時の表示
                $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px; white-space: nowrap;">' . $reservePartName . '参加</p>';
                $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
            } else {  //今日以降の日にち→つまり未来日
                // $html[] = '<button type="button" class="btn btn-danger p-0 w-75" name="delete_date" style="font-size:12px" value="'. $day->authReserveDate($day->everyDay())->first()->setting_reserve .'" data-toggle="modal" data-target="#deleteModal" onclick="setDeleteValue(this.value)">'. $reservePart .'</button>';
                $html[] = '<button type="button" class="btn btn-danger p-0 w-75" name="delete_date" style="font-size:12px" value="' . $reserveId . '" data-toggle="modal" data-target="#deleteModal" onclick="setDeleteValue(\'' . $reserveId . '\', \'' . $reserveDate . '\', \'' . $reservePart . '\', \'' . $reservePartName . '\')">' . $reservePartName . '</button>';
                $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
            }
        } else {  //予約がない日
            if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {  //今日より前の日にち→つまり過去日
              $html[] = '<p class="text-center text-muted">受付終了</p>';
              $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
            } else {
              $html[] = $day->selectPart($day->everyDay());
            }
        }
        
        $html[] = $day->getDate();
        $html[] = '</td>';
        
        }
        $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">'.csrf_field().'</form>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">'.csrf_field().'</form>';

    // モーダル部分を追加
    $html[] = '<!-- 削除確認モーダル -->';
    $html[] = '<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">';
    $html[] = '<div class="modal-dialog">';
    $html[] = '<div class="modal-content">';
    $html[] = '<div class="modal-header">';
    $html[] = '<button type="button" class="close" data-dismiss="modal" aria-label="閉じる">';
    $html[] = '<span aria-hidden="true">&times;</span>';
    $html[] = '</button>';
    $html[] = '</div>';
    $html[] = '<div class="modal-body">';
    $html[] = '<div class="mb-2">'; // 予約日を表示するためのdiv
    $html[] = '予約日:<span id="reservationDate"></span>'; // 予約日を表示
    $html[] = '</div>';
    $html[] = '<div class="mb-2">'; // 時間を表示するためのdiv
    $html[] = '時間:<span id="reservationTime"></span>'; // 時間を表示
    $html[] = '</div>';
    $html[] = '<div>'; // 確認メッセージ
    $html[] = '上記の予約をキャンセルしてもよろしいですか？';
    $html[] = '</div>';
    $html[] = '</div>';
    $html[] = '<div class="modal-footer">';
    $html[] = '<button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">'.csrf_field();
    $html[] = '<input type="hidden" name="delete_date" id="deleteDateValue" value="">';
    $html[] = '<input type="hidden" name="delete_part" id="deletePartValue" value="">';
    $html[] = '<button type="submit" class="btn btn-danger">キャンセル</button>';
    $html[] = '</form>';
    $html[] = '</div>';
    $html[] = '</div>';
    $html[] = '</div>';
    $html[] = '</div>';


    return implode('', $html);
  }

  protected function getWeeks(){
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while($tmpDay->lte($lastDay)){
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}