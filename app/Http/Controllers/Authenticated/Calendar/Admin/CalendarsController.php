<?php

namespace App\Http\Controllers\Authenticated\Calendar\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\Admin\CalendarView;
use App\Calendars\Admin\CalendarSettingView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarsController extends Controller
{
    public function show(){
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.admin.calendar', compact('calendar'));
    }

    public function reserveDetail($date, $part){
        $reservePersons = ReserveSettings::with('users')->where('setting_reserve', $date)->where('setting_part', $part)->get();
        return view('authenticated.calendar.admin.reserve_detail', compact('reservePersons', 'date', 'part'));
    }

    public function reserveSettings(){
        $calendar = new CalendarSettingView(time());
        return view('authenticated.calendar.admin.reserve_setting', compact('calendar'));
    }

    public function updateSettings(Request $request){
        $reserveDays = $request->input('reserve_day');
        foreach($reserveDays as $day => $parts){
            foreach($parts as $part => $frame){
                ReserveSettings::updateOrCreate([
                    'setting_reserve' => $day,
                    'setting_part' => $part,
                ],[
                    'setting_reserve' => $day,
                    'setting_part' => $part,
                    'limit_users' => $frame,
                ]);
            }
        }
        return redirect()->route('calendar.admin.setting', ['user_id' => Auth::id()]);
    }

    // public function delete(Request $request){
    //     DB::beginTransaction();
    //     try{
    //         // リクエストから削除する予約の設定IDを取得
    //         $reserveDate = $request->input('delete_date');
    //         $reservePart = $request->input('delete_part');
            
    //         // 該当の予約設定を取得
    //         $reserve_settings = ReserveSettings::where('setting_reserve', $reserveDate)->where('setting_part', $reservePart)->first();

    //         // 予約設定が存在し、ログインユーザーがその予約を持っているか確認
    //         if ($reserve_settings && $reserve_settings->users()->where('user_id', Auth::id())->exists()) {
    //             // ユーザーの予約を解除し、予約枠の人数を増やす
    //             $reserve_settings->users()->detach(Auth::id());
    //             $reserve_settings->increment('limit_users');
    //         }

    //         DB::commit();
    //     }catch(\Exception $e){
    //         DB::rollback();
    //         return redirect()->back()->with('error', '予約の削除に失敗しました。');
    //     }

    //     return redirect()->route('calendar.general.show', ['user_id' => Auth::id()])->with('success', '予約が削除されました。');
    // }
}
