<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarsController extends Controller
{
    public function show(){
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }

    public function reserve(Request $request){
        DB::beginTransaction();
        try{
            $getPart = $request->getPart;
            $getDate = $request->getData;

            // dd($request);

            $filteredParts = array_filter($getPart);
            $filteredDates = array_intersect_key($getDate, $filteredParts);

            if (count($filteredDates) !== count($filteredParts)) {
                return redirect()->back();
            }
            
            $reserveDays = array_filter(array_combine($getDate, $getPart));
            foreach($reserveDays as $key => $value){
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
                // dd($reserve_settings);
                $reserve_settings->decrement('limit_users');
                $reserve_settings->users()->attach(Auth::id());
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

    public function delete(Request $request) {
        DB::beginTransaction();
        try{
            //　リクエストから削除する予約の設定IDを取得
            $reserveDate = $request->input('delete_date');
            $reservePart = $request->input('delete_part');
            
            // if ($reservePart == 1) {
            //     $reservePart = "リモ1部";
            // } else if ($reservePart == 2) {
            //     $reservePart = "リモ２部";
            // } else if ($reservePart == 3) {
            //     $reservePart = "リモ３部";
            // }

            //  dd($reserveDate,$reservePart);
            //　該当の予約設定を取得
            $reserve_settings = ReserveSettings::where('setting_reserve', $reserveDate)->where('setting_part',$reservePart)->first();

            // dd($reserveDate, $reservePart);
            // dd($reserve_settings);

            //　予約設定が存在し、ログインユーザーがその予約を持っているか確認
            if ($reserve_settings && $reserve_settings->users()->where('user_id', Auth::id())->exists()) {
                // ユーザーの予約を解除し、予約枠の人数を増やす
                $reserve_settings->users()->detach(Auth::id());
                $reserve_settings->increment('limit_users');

            }
            // $reserve_settings->delete();

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            return redirect()->back();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }
}