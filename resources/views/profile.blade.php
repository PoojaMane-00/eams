@extends('layouts.sideheader')

@section('content')
<div class="page-content-wrapper">
    <div class="container-fluid">

        <!-- Page Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="btn-group float-right">
                        <ol class="breadcrumb hide-phone p-0 m-0">
                            <li class="breadcrumb-item"><a href="#">EAMS</a></li>
                            <li class="breadcrumb-item active">Employee Form</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Add Employee Details</h4>
                </div>
            </div>
        </div>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        @endif

        <!-- Form Card -->
        <form method="POST" id="profileForm" action="{{ route('saveprofile') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card m-b-30">
                        <div class="card-body">

                            <!-- Employment Details -->
                            <h4 class="header-title text-center mt-5 mb-4">Employment Details</h4>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Employee ID</label>
                                <div class="col-sm-10">
                                    <input type="text" name="employee_id" class="form-control" value="{{ $profile->employee_id ?? '' }}" placeholder="Auto-generated or manual">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Department</label>
                                <div class="col-sm-10">
                                    <input type="text" name="department" class="form-control" value="{{ $profile->department ?? '' }}" placeholder="e.g. HR, IT, Finance">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Designation</label>
                                <div class="col-sm-10">
                                    <input type="text" name="designation" class="form-control" value="{{ $profile->designation ?? '' }}" placeholder="e.g. Software Engineer">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Date of Joining</label>
                                <div class="col-sm-10">
                                    <input type="date" name="joining_date" class="form-control" value="{{ $profile->joining_date ?? '' }}">
                                </div>
                            </div>

                            <!-- Personal Details -->
                            <h4 class="header-title text-center mb-4">Personal Details</h4>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Full Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control" value="{{ $profile->name ?? '' }}" placeholder="Enter full name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Date of Birth</label>
                                <div class="col-sm-10">
                                    <input type="date" name="dob" class="form-control" value="{{ $profile->dob ?? '' }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" name="email" class="form-control" value="{{ $profile->email ?? '' }}" placeholder="Enter email address">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Mobile</label>
                                <div class="col-sm-10">
                                    <input type="text" name="mobile" class="form-control" value="{{ $profile->mobile ?? '' }}" placeholder="Enter mobile number" maxlength="10" minlength="10">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Gender</label>
                                <div class="col-sm-10">
                                    <select name="gender" class="form-control">
                                        <option value="">Select</option>
                                        <option value="male" {{ $profile->gender === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ $profile->gender === 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ $profile->gender === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Marital Status</label>
                                <div class="col-sm-10">
                                    <select name="marital_status" class="form-control">
                                        <option value="">Select</option>
                                        <option value="single">Single</option>
                                        <option value="married">Married</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Address</label>
                                <div class="col-sm-10">
                                    <textarea name="address" class="form-control" rows="2" placeholder="Enter full address">{{ $profile->address ?? '' }}</textarea>
                                </div>
                            </div>

                            <!-- Financial & Compliance -->
                            <h4 class="header-title text-center mt-5 mb-4">Financial & Compliance Details</h4>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Bank Account Number</label>
                                <div class="col-sm-10">
                                    <input type="text" name="bank_account" class="form-control" value="{{ $profile->bank_account ?? '' }}" placeholder="Enter account number">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">UAN (PF)</label>
                                <div class="col-sm-10">
                                    <input type="text" name="uan" class="form-control" value="{{ $profile->bank_account ?? '' }}" placeholder="Universal Account Number">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">ESIC Number</label>
                                <div class="col-sm-10">
                                    <input type="text" name="esic" class="form-control" value="{{ $profile->esic ?? '' }}" placeholder="If applicable">
                                </div>
                            </div>

                            <!-- Educational Details -->
                            <h4 class="header-title text-center mt-5 mb-4">Educational Details</h4>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Highest Qualification</label>
                                <div class="col-sm-10">
                                    <input type="text" name="qualification" class="form-control" value="{{ $profile->qualification ?? '' }}" placeholder="e.g. B.Tech, MBA">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">University/College</label>
                                <div class="col-sm-10">
                                    <input type="text" name="institution" class="form-control" value="{{ $profile->institution ?? '' }}" placeholder="Enter institution name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Year of Passing</label>
                                <div class="col-sm-10">
                                    <input type="number" name="passing_year" class="form-control" value="{{ $profile->passing_year ?? '' }}" placeholder="e.g. 2022">
                                </div>
                            </div>

                            <!-- Document Uploads -->
                            <h4 class="header-title text-center mt-5 mb-4">Document Uploads</h4>

                            @php
                            $documents = $profile->documents->keyBy('document_type');
                            @endphp

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-4 col-form-label">PAN Card*</label>
                                        <div class="col-sm-8">
                                            <input type="file" accept=".pdf,.doc,.docx" name="pan_card" class="form-control-file">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-4 col-form-label">Aadhaar Card*</label>
                                        <div class="col-sm-8">
                                            <input type="file" accept=".pdf,.doc,.docx" name="aadhaar_card" class="form-control-file">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-4 col-form-label">Education Certificates</label>
                                        <div class="col-sm-8">
                                            <input type="file" accept=".pdf,.doc,.docx" name="education_certificates[]" class="form-control-file" multiple>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-4 col-form-label">Experience Letters</label>
                                        <div class="col-sm-8">
                                            <input type="file" accept=".pdf,.doc,.docx" name="experience_letters[]" class="form-control-file" multiple>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-4 col-form-label">NDA*</label>
                                        <div class="col-sm-8">
                                            <input type="file" accept=".pdf,.doc,.docx" name="nda" class="form-control-file">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-4 col-form-label">Passbook Copy*</label>
                                        <div class="col-sm-8">
                                            <input type="file" accept=".pdf,.doc,.docx" name="passbook" class="form-control-file">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Submit Button -->
                            <div class="form-group row mt-4">
                                <div class="col-sm-12 text-center">
                                    <button type="submit" class="btn btn-success">Submit Details</button>
                                </div>
                            </div>

                            <!-- Uploaded Documents -->
                            <div class="row">
                                @if($profile->documents->isNotEmpty())
                                <div class="col-12">
                                    <div class="alert alert-warning text-center">
                                        Uploaded documents
                                    </div>
                                </div>
                                @endif
                                @forelse ($profile->documents as $doc)
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card h-100 shadow-sm border-0">
                                        <div class="card-body p-2 d-flex flex-column justify-content-between">
                                            <div>
                                                <h6 class="mb-1 text-truncate">{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</h6>
                                                <small class="text-muted text-truncate d-block">{{ basename($doc->file_path) }}</small>
                                            </div>
                                            <div class="mt-2 text-end">
                                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="mdi mdi-eye"></i> View
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="alert alert-warning text-center">
                                        Uploaded documents will be displayed here
                                    </div>
                                </div>
                                @endforelse
                            </div>

                        </div> <!-- card-body -->
                    </div> <!-- card -->
                </div> <!-- col-12 -->
            </div> <!-- row -->
        </form>

    </div> <!-- container-fluid -->
</div> <!-- page-content-wrapper -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

        function validateFileInput(inputName) {
            const input = document.querySelector(`input[name="${inputName}"]`);
            input.addEventListener('change', function() {
                const file = this.files[0];
                const errorContainer = this.nextElementSibling;

                // Remove previous error
                if (errorContainer && errorContainer.classList.contains('text-danger')) {
                    errorContainer.remove();
                }

                if (file && !allowedTypes.includes(file.type)) {
                    const error = document.createElement('div');
                    error.classList.add('text-danger', 'mt-1');
                    error.innerText = 'Invalid file type. Only PDF, DOC, DOCX allowed.';
                    this.parentNode.appendChild(error);
                    this.value = ''; // Clear invalid file
                }
            });
        }

        // Single file fields
        ['resume', 'pan_card', 'aadhaar_card', 'address_proof', 'employment_contract', 'nda', 'form16', 'passbook'].forEach(validateFileInput);

        // Multiple file fields
        ['education_certificates', 'experience_letters'].forEach(field => {
            const input = document.querySelector(`input[name="${field}[]"]`);
            input.addEventListener('change', function() {
                const files = this.files;
                const errorContainer = this.nextElementSibling;

                if (errorContainer && errorContainer.classList.contains('text-danger')) {
                    errorContainer.remove();
                }

                for (let file of files) {
                    if (!allowedTypes.includes(file.type)) {
                        const error = document.createElement('div');
                        error.classList.add('text-danger', 'mt-1');
                        error.innerText = 'One or more files are invalid. Only PDF, DOC, DOCX allowed.';
                        this.parentNode.appendChild(error);
                        this.value = ''; // Clear all files
                        break;
                    }
                }
            });
        });
    });
</script>

<script>
    document.getElementById('resumeInput').addEventListener('change', function() {
        const file = this.files[0];
        const preview = document.getElementById('resumePreview');
        preview.innerHTML = file ? `Selected: ${file.name}` : '';
    });
</script>
@endsection