<?php

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Seiger\sMailer\Controllers\sMailerController;
use Seiger\sMailer\Models\sMailerUser;

if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') die("No access");

$сontroller = new sMailerController();
$data['сontroller'] = $сontroller;
$data['editor'] = '';
$data['tabs'] = [];
$data['get'] = request()->input('get', 'configure');
$data['url'] = $сontroller->url;

switch ($data['get']) {
    default:
        $data['tabs'] = ['configure', 'periodic', 'once', 'subscribers'];
        $data['editor'] = $сontroller->textEditor('footer_text');
        if (request()->isMethod('POST')) {
            $сontroller->updateConfigure();
            return header('Location: ' . $сontroller->url . '&get=configure');
        }
        break;
    case "periodic":
        $data['tabs'] = ['configure', 'periodic', 'once', 'subscribers'];
        if (request()->isMethod('POST')) {
            $сontroller->updateConfigure();
            return header('Location: ' . $сontroller->url . '&get=periodic');
        }
        break;
    case "periodic-preview":
        \View::getFinder()->setPaths([evo()->resourcePath('modules/smailer')]);
        return \View::make('periodicTemplate');
        break;
    case "subscribers":
        Paginator::defaultView('sMailer::partials.pagination');
        $perpage = Cookie::get('s_mailer_page_items', 50);
        $order = request()->input('order', 'id');
        $direc = request()->input('direc', 'desc');
        $data['tabs'] = ['configure', 'periodic', 'once', 'subscribers'];
        $data['subscribers'] = sMailerUser::addSelect('*', DB::Raw('(CASE WHEN `subscribe` = 1 and `blocked` = 0 THEN 1 WHEN `subscribe` = 1 and `blocked` = 1 THEN 2 ELSE 3 END) as status'))
            ->search()
            ->orderBy($order, $direc)
            ->paginate($perpage);
        $data['total'] = number_format(sMailerUser::all()->count(), 0, '', ' ');
        $data['active'] = number_format(sMailerUser::whereBlocked(0)->whereSubscribe(1)->get()->count(), 0, '', ' ');
        $data['blocked'] = number_format(sMailerUser::whereBlocked(1)->whereSubscribe(1)->get()->count(), 0, '', ' ');
        $data['unsubscribe'] = number_format(sMailerUser::whereSubscribe(0)->get()->count(), 0, '', ' ');
        break;
    case "user-lock":
        $responce['status'] = 0;
        $subscriber = sMailerUser::find(request()->input('subscriber', 0));
        if ($subscriber) {
            if ($subscriber->blocked == 1) {
                $subscriber->blocked = 0;
            } else {
                $subscriber->blocked = 1;
            }
            $subscriber->update();
            $responce['status'] = 1;
        }
        die(json_encode($responce));
    case "user-delete":
        $responce['status'] = 0;
        $subscriber = sMailerUser::find(request()->input('subscriber', 0));
        if ($subscriber) {
            $subscriber->delete();
            $responce['status'] = 1;
        }
        die(json_encode($responce));
}

echo $сontroller->view('index', $data);
