<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Ad;
use Illuminate\Support\Facades\Auth;

use App\Services\ServerStatusService;

class DashboardController extends Controller
{
    protected $serverStatusService;

    public function __construct(ServerStatusService $serverStatusService)
    {
        $this->serverStatusService = $serverStatusService;
    }
    public function index()
    {
        $serverStatus = $this->serverStatusService->checkStatus();
        $serverStatus = $serverStatus['overall']; // 獲取整體狀態字串
        $onlineUsers = $this->serverStatusService->getOnlineUsers();

        $employees = Employee::count();
        $pendingReviews = 810; // 假設數據，需從實際資料來源替換
        $currentEmployee = Auth::guard('employee')->user();
        $permissionLevel = $currentEmployee->permission ? $currentEmployee->permission->permission_level : '未知';


        return view('admin.dashboard', compact(
            'employees',
            'pendingReviews',
            'permissionLevel',
            'serverStatus',
            'onlineUsers'
        ));
    }

    public function ads()
    {
        // 第一個廣告
        $ad1 = Ad::where('ad_id', 1)->first();
        $markdownText = $ad1 ? $ad1->ad_content : '';

        // Convert Markdown to HTML
        $converter = new \League\CommonMark\CommonMarkConverter(['html_input' => 'strip', 'allow_unsafe_links' => false]);
        $htmlContent = $converter->convert($markdownText)->getContent();

        // Pass to Blade view
        return view('admin.dashboard', compact('htmlContent'));
    }

    public function products()
    {
        return view('admin.dashboard');
    }

    public function inventory()
    {
        return view('admin.dashboard');
    }

    public function inventorySearch()
    {
        return view('admin.dashboard');
    }
    public function getServerStatus()
    {
        $status = $this->serverStatusService->checkStatus();
        $onlineUsers = $this->serverStatusService->getOnlineUsers();

        return response()->json([
            'serverStatus' => $status['overall'], // Return the 'overall' string
            'onlineUsers' => $onlineUsers,
        ]);
    }
}
