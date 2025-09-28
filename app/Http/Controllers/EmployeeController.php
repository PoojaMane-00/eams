<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    // public function index()
    // {
    //     $employees = Employee::all(); // Or paginate if needed
    //     return view('employee.index', compact('employees'));
    // }


    // public function login(Request $request)
    // {
    //     $credentials = $request->only('email', 'password');

    //     if (Auth::guard('employee')->attempt($credentials)) {
    //         $employee = Auth::guard('employee')->user();
    //         session(['employee_id' => $employee->id]); // Optional custom session data
    //         return redirect()->route('employee.dashboard');
    //     }

    //     return back()->withErrors(['email' => 'Invalid employee credentials']);
    // }


    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'employee_id' => 'nullable|string',
            'department' => 'required|string',
            'designation' => 'required|string',
            'joining_date' => 'required|date',
            'name' => 'required|string',
            'dob' => 'required|date',
            'email' => 'required|email|unique:employees',
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

        // Store the basic employee information
        $employeeData = $request->only([
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
        ]);

        // Create the employee record first
        $employee = Employee::create($employeeData);

        // Handle and store the files
        $this->uploadDocument($request, 'resume', $employee);
        $this->uploadDocument($request, 'pan_card', $employee);
        $this->uploadDocument($request, 'aadhaar_card', $employee);
        $this->uploadDocument($request, 'address_proof', $employee);
        $this->uploadMultipleDocuments($request, 'education_certificates', $employee);
        $this->uploadMultipleDocuments($request, 'experience_letters', $employee);
        $this->uploadDocument($request, 'employment_contract', $employee);
        $this->uploadDocument($request, 'nda', $employee);
        $this->uploadDocument($request, 'form16', $employee);
        $this->uploadDocument($request, 'passbook', $employee);

        return redirect()->route('employee.index')->with('success', 'Employee details added successfully');
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
}
