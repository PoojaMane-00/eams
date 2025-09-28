<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Models\Attendance;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\SalarySlip;
use Carbon\Carbon;

class Home extends Controller
{

    // EMPLOYEE GET AND POST FUNCTIONS
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            // Validate input
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $email = $request->input('email');
            $password = $request->input('password');

            // Check if employee exists with matching email and plain password
            $employee = Employee::where('email', $email)
                ->where('password', $password) // ⚠️ Plain password check
                ->first();
            $query = Employee::where('email', $email)
                ->where('password', $password);

            if ($employee) {
                // Set session data
                session([
                    'user_type' => 'employee',
                    'user_id' => $employee->id,
                    'user_email' => $employee->email,
                    'user_name' => $employee->name
                ]);

                return redirect()->intended('empdashboard');
            }

            // If login fails
            return back()->withErrors([
                'email' => 'Invalid credentials.',
            ])->withInput();
        }

        // Show login form on GET request
        return view('employee.login', ['view_type' => 'employee']);
    }


    public function admin()
    {
        return view('login', ['view_type' => 'admin']);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            session(['user_type' => "admin"]);

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function empdashboard()
    {
        if (!session()->has('user_type')) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        $userId = session('user_id');
        $today = Carbon::today()->toDateString();

        $punches = Attendance::where('employee_id', $userId)
            ->whereDate('date', $today)
            ->first();

        return view('employee.dashboard', compact('punches'));
    }

    public function dashboard()
    {
        if (!session()->has('user_type')) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        $userId = session('user_id'); // Changed from user_type to user_id
        $today = Carbon::today()->toDateString();

        $punches = Attendance::where('employee_id', $userId)
            ->whereDate('date', $today)
            ->first();

        $employeeCount = Employee::count(); // Equivalent to SELECT COUNT(*) FROM public.employees

        return view('dashboard', compact('punches', 'employeeCount'));
    }

    public function employees()
    {
        if (!session()->has('user_type')) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        $employees = Employee::orderBy('employee_id', 'asc')->get();
        return view('employee.employees', compact('employees'));
    }

    public function attendance()
    {
        if (!session()->has('user_type')) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        $employees = Employee::orderBy('employee_id', 'asc')->get();
        return view('employee.attendance', compact('employees'));
    }

    // Load profile page view
    public function profile()
    {
        if (!session()->has('user_type')) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        $userId = session('user_id'); // Get logged-in user ID from session
        // $profile = Employee::find($userId);
        $profile = Employee::with('documents')->find($userId);

        if (!$profile) {
            $profile = new Employee(); // Create empty model to avoid null errors
        }
        return view('profile', compact('profile'));
    }

    // Function to save Profile Data
    public function saveprofile(Request $request)
    {
        if (!session()->has('user_type')) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        $request->validate([
            'employee_id' => 'nullable|string',
            'department' => 'required|string',
            'designation' => 'required|string',
            'joining_date' => 'required|date',
            'name' => 'required|string',
            'dob' => 'required|date',
            'email' => 'required|email|unique:employees,email,' . session('user_id'),
            'mobile' => 'required|string',
            'gender' => 'required|in:male,female,other',
            'marital_status' => 'nullable|in:single,married',
            'address' => 'required|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx',
            'pan_card' => 'nullable|file|mimes:pdf,doc,docx',
            'aadhaar_card' => 'nullable|file|mimes:pdf,doc,docx',
            'address_proof' => 'nullable|file|mimes:pdf,doc,docx',
            'education_certificates' => 'nullable|array',
            'education_certificates.*' => 'file|mimes:pdf,doc,docx',
            'experience_letters' => 'nullable|array',
            'experience_letters.*' => 'file|mimes:pdf,doc,docx',
            'employment_contract' => 'nullable|file|mimes:pdf,doc,docx',
            'nda' => 'nullable|file|mimes:pdf,doc,docx',
            'form16' => 'nullable|file|mimes:pdf,doc,docx',
            'passbook' => 'nullable|file|mimes:pdf,doc,docx',
        ]);

        $userId = session('user_id');

        $employee = Employee::updateOrCreate(
            ['id' => $userId],
            $request->only([
                'employee_id',
                'department',
                'designation',
                'joining_date',
                'name',
                'dob',
                'email',
                'mobile',
                'gender',
                'marital_status',
                'address',
                'bank_account',
                'uan',
                'esic',
                'qualification',
                'institution',
                'passing_year'
            ])
        );

        // Handle single file uploads
        foreach (['resume', 'pan_card', 'aadhaar_card', 'address_proof', 'employment_contract', 'nda', 'form16', 'passbook'] as $docType) {
            if ($request->hasFile($docType)) {
                $file = $request->file($docType);
                $extension = $file->getClientOriginalExtension();
                $filename = "{$employee->id}_{$docType}." . $extension;
                $path = $file->storeAs("documents/{$employee->id}", $filename, 'public');

                $existing = EmployeeDocument::where('employee_id', $employee->id)
                    ->where('document_type', $docType)
                    ->first();

                if ($existing && Storage::disk('public')->exists($existing->file_path)) {
                    Storage::disk('public')->delete($existing->file_path);
                }

                EmployeeDocument::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'document_type' => $docType,
                    ],
                    [
                        'file_path' => $path,
                    ]
                );
            }
        }

        // Handle multiple file uploads (no overwrite, allow multiple entries)
        foreach (['education_certificates', 'experience_letters'] as $docType) {
            if ($request->hasFile($docType)) {
                foreach ($request->file($docType) as $index => $file) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = "{$employee->id}_{$docType}_{$index}." . $extension;
                    $path = $file->storeAs("documents/{$employee->id}/{$docType}", $filename, 'public');

                    EmployeeDocument::create([
                        'employee_id' => $employee->id,
                        'document_type' => $docType,
                        'file_path' => $path,
                    ]);
                }
            }
        }

        return redirect()->route('profile')->with('success', 'Profile saved successfully!');
    }


    public function create(Request $request)
    {

        if (!session()->has('user_type')) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|string|min:6',
        ]);

        // Get last employee_id and increment
        $lastEmployee = Employee::orderBy('employee_id', 'desc')->first();
        $newEmployeeId = $lastEmployee ? $lastEmployee->employee_id + 1 : 1001;

        Employee::create([
            'employee_id' => $newEmployeeId,
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return redirect()->route('employees')->with('success', 'Employee added successfully!');
    }

    // Helper method to upload and save a single document
    private function uploadDocument($request, $field, $employee)
    {
        if ($request->hasFile($field)) {
            $filePath = $request->file($field)->store('employee_documents');
            EmployeeDocument::create([
                'employee_id' => $employee->id,
                'document_type' => $field,
                'file_path' => $filePath,
            ]);
        }
    }

    // Helper method to upload and save multiple documents
    private function uploadMultipleDocuments($request, $field, $employee)
    {
        if ($request->hasFile($field)) {
            foreach ($request->file($field) as $file) {
                $filePath = $file->store('employee_documents');
                EmployeeDocument::create([
                    'employee_id' => $employee->id,
                    'document_type' => $field,
                    'file_path' => $filePath,
                ]);
            }
        }
    }

    public function punch(Request $request)
    {
        if (!session()->has('user_type')) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        $employeeId = session('user_id');
        $date = Carbon::today()->toDateString();
        $time = Carbon::now()->format('H:i:s');
        $action = $request->input('action'); // 'in' or 'out'

        $attendance = Attendance::firstOrNew([
            'employee_id' => $employeeId,
            'date' => $date,
        ]);

        $attendance->date = $date;

        $message = '';
        if ($action === 'in') {
            if (!$attendance->punch_in) {
                $attendance->punch_in = $time; // first punch-in
                $attendance->status = 'present';
                $message = 'First punch-in recorded.';
            } else {
                $message = 'Already punched in. First punch-in preserved.';
            }
        } elseif ($action === 'out') {
            $attendance->punch_out = $time; // always update to latest punch-out
            $message = 'Punch-out updated to latest time.';
        }

        $attendance->save();

        return response()->json([
            'status' => 'success',
            'action' => $action,
            'time' => Carbon::parse($time)->format('H:i'),
            'message' => $message
        ]);
    }


    public function getPunchTimes(Request $request)
    {
        if (!session()->has('user_type')) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        $userId = $request->user_id ?? session('user_id');
        $date = Carbon::parse($request->date)->toDateString();

        $punch = Attendance::where('employee_id', $userId)
            ->whereDate('date', $date)
            ->first();

        return response()->json([
            'in' => $punch && $punch->punch_in ? Carbon::parse($punch->punch_in)->format('H:i') : '00:00',
            'out' => $punch && $punch->punch_out ? Carbon::parse($punch->punch_out)->format('H:i') : '00:00',
        ]);
    }

    public function getMonthStatus(Request $request)
    {
        if (!session()->has('user_type')) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        // Use user_id from request if provided, otherwise fallback to session
        $userId = $request->user_id ?? session('user_id');
        // die;
        $year = $request->year;
        $month = $request->month;

        $attendances = Attendance::where('employee_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        $statusMap = [];

        foreach ($attendances as $record) {
            $in = $record->punch_in;
            $out = $record->punch_out;

            if ($in && $out) {
                $hours = \Carbon\Carbon::parse($in)->diffInMinutes(\Carbon\Carbon::parse($out)) / 60;

                if ($hours >= 9) {
                    $statusMap[$record->date] = 'success'; // green
                } elseif ($hours >= 4) {
                    $statusMap[$record->date] = 'warning'; // yellow
                } else {
                    $statusMap[$record->date] = 'danger'; // red
                }
            } else {
                $statusMap[$record->date] = 'danger'; // red
            }
        }

        return response()->json($statusMap);
    }

    public function updatePunch(Request $request)
    {
        $attendance = Attendance::where('employee_id', $request->user_id)
            ->whereDate('date', $request->date)
            ->first();

        if ($attendance) {
            $attendance->punch_in = $request->punch_in;
            $attendance->punch_out = $request->punch_out;
            $attendance->save();

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error', 'message' => 'Record not found'], 404);
    }


    public function view($userId)
    {

        if (!session()->has('user_type')) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        $today = \Carbon\Carbon::today()->toDateString();

        $punches = Attendance::where('employee_id', $userId)
            ->whereDate('date', $today)
            ->first();

        $employee = \App\Models\Employee::find($userId);
        return view('employee.view', compact('userId', 'punches', 'employee'));
    }

    public function profileview($userId)
    {
        if (!session()->has('user_type')) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        // $userId = $request->user_id;
        // $profile = Employee::find($userId);
        $profile = Employee::with('documents')->find($userId);

        if (!$profile) {
            $profile = new Employee(); // Create empty model to avoid null errors
        }
        return view('employee.profileview', compact('profile'));
    }

    public function slip($userId = null)
    {
        if (!session()->has('user_type')) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        $userId = session('user_id') ?? $userId;
        $slip = SalarySlip::where('employee_id', $userId)->latest('salary_month')->first();

        if (!$slip) {
            return redirect()->back()->with('error', 'No salary slip found for this employee.');
        }

        return view('employee.slip', compact('slip'));
    }


    // public function generateSalarySlip($employeeId, $month)
    // {
    //     $employee = Employee::findOrFail($employeeId);

    //     // Example salary components — replace with actual logic or DB values
    //     $basic = 30000;
    //     $hra = 10000;
    //     $allowances = 5000;
    //     $deductions = 4000;
    //     $net = $basic + $hra + $allowances - $deductions;

    //     $slip = SalarySlip::create([
    //         'employee_id' => $employee->id,
    //         'salary_month' => $month,
    //         'basic_salary' => $basic,
    //         'hra' => $hra,
    //         'allowances' => $allowances,
    //         'deductions' => $deductions,
    //         'net_salary' => $net,
    //     ]);

    //     return view('slip', compact('slip'));
    // }


    public function logout()
    {
        return redirect()->intended('/');
    }

    public function forgotpass()
    {
        return "Have You forgot your password? Reset Here 👆";
    }
}
