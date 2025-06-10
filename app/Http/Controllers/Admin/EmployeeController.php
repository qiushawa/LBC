<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    /**
     * 顯示員工列表
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        // 員工總數
        $totalEmployees = Employee::count();
        $employees = Employee::with('permission')
            ->when($search, function ($query, $search) {
                return $query->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
                    ->orWhere('employee_email', 'like', "%{$search}%");
            })
            ->paginate(10);

        $employees->appends(['search' => $search]);
        return view('admin.dashboard', compact('employees', 'totalEmployees'));
    }

    /**
     * 顯示新增員工表單
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.employees.create', compact('permissions'));
    }

    /**
     * 儲存新員工
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'employee_email' => 'required|email|unique:employees,employee_email',
            'password' => 'required|string|min:8|confirmed',
            'permission_id' => 'required|exists:permissions,permission_id',
        ]);

        Employee::create([
            'employee_name' => $request->employee_name,
            'employee_email' => $request->employee_email,
            'password' => Hash::make($request->password),
            'permission_id' => $request->permission_id,
        ]);

        return redirect()->route('admin.employees')->with('success', '員工新增成功');
    }

    /**
     * 顯示編輯員工表單
     */
    public function edit(Employee $employee)
    {
        $permissions = Permission::all();
        return view('admin.employees.edit', compact('employee', 'permissions'));
    }

    /**
     * 更新員工資料
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'employee_email' => 'required|email|unique:employees,employee_email,' . $employee->employee_id . ',employee_id',
            'permission_id' => 'required|exists:permissions,permission_id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $employee->update([
            'employee_name' => $request->employee_name,
            'employee_email' => $request->employee_email,
            'permission_id' => $request->permission_id,
            'password' => $request->password ? Hash::make($request->password) : $employee->password,
        ]);

        return redirect()->route('admin.employees')->with('success', '員工資料更新成功');
    }

    /**
     * 刪除員工
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('admin.employees')->with('success', '員工已刪除');
    }
    public function uploadImage(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'image' => 'required|image|mimes:png,jpeg|max:2048', // Max 2MB
            ]);

            $employee = Auth::guard('employee')->user();
            if (!$employee) {
                return redirect()->back()->withErrors(['error' => '未找到已認證的員工']);
            }

            $email = $employee->employee_email;
            $filename = $email . '.png';
            $path = $filename; // Store directly in employee_images/

            // Store the image in the private disk
            $request->file('image')->storeAs('', $filename, 'employee_images');

            // Verify the file was saved
            if (!Storage::disk('employee_images')->exists($path)) {
                Log::error('Image not saved at path: ' . $path);
                return redirect()->back()->withErrors(['error' => '圖片儲存失敗']);
            }

            return redirect()->back()->with('success', '圖片上傳成功！');
        } catch (\Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => '圖片上傳失敗：' . $e->getMessage()]);
        }
    }
    public function serveImage($filename)
    {
        try {
            // Ensure the user is authenticated
            if (!Auth::guard('employee')->check()) {
                abort(403, '未授權存取');
            }

            // Validate filename to prevent directory traversal
            if (!preg_match('/^[a-zA-Z0-9_\-\.@]+\.png$/', $filename)) {
                abort(404, '無效的文件名稱');
            }

            $path = $filename; // File is stored directly in employee_images/

            // Check if the file exists
            if (!Storage::disk('employee_images')->exists($path)) {
                // Fallback to default.png
                if ($filename !== 'default.png') {
                    $path = 'default.png';
                    if (!Storage::disk('employee_images')->exists($path)) {
                        abort(404, '圖片未找到');
                    }
                } else {
                    abort(404, '圖片未找到');
                }
            }

            // Serve the image with proper headers
            $file = Storage::disk('employee_images')->get($path);
            $fullPath = Storage::disk('employee_images')->path($path);
            $mime = function_exists('mime_content_type') ? mime_content_type($fullPath) : 'image/png';

            return response($file, 200)->header('Content-Type', $mime);
        } catch (\Exception $e) {
            Log::error('Error serving image: ' . $e->getMessage());
            abort(500, '無法提供圖片');
        }
    }
}
