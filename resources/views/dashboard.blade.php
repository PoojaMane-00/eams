@extends('layouts.sideheader')
@section('content')
<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="btn-group float-right">
                        <ol class="breadcrumb hide-phone p-0 m-0">
                            <li class="breadcrumb-item"><a href="#">SBMS</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Dashboard {{ session('user_type') }}</h4>
                </div>
            </div>
        </div>
        <!-- end page title end breadcrumb -->
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

        @php
        $today = \Carbon\Carbon::today();
        $attendance = \App\Models\Attendance::where('employee_id', session('user_id'))
        ->whereDate('date', $today)
        ->first();
        @endphp
        <div class="row"><!-- Column -->
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="card text-white mb-4" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                        <h6 class="mb-1">Total Employees</h6>
                        <h2 class="fw-bold mb-0">{{ $employeeCount }}</h2>
                    </div>
                </div>
            </div>


            <div class="col-md-6 col-lg-6 col-xl-3">
                <a href="{{ route('attendance') }}" style="text-decoration: none;">
                    <div class="card text-white mb-4" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none;">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                            <h6 class="mb-1">Manage Attendance</h6>
                            <h2 class="fw-bold mb-0">→</h2> <!-- Optional icon or placeholder for visual balance -->
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-6 col-xl-3">
                <a href="{{ route('employees') }}" style="text-decoration: none;">
                    <div class="card text-white mb-4" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none;">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                            <h6 class="mb-1">Manage Profiles</h6>
                            <h2 class="fw-bold mb-0">→</h2> <!-- Optional icon or placeholder for visual balance -->
                        </div>
                    </div>
                </a>
            </div>



            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="col-3 align-self-center">
                                <div class="round"><i class="mdi mdi-glassdoor"></i></div>
                            </div>
                            <div class="col-9 align-self-center">
                                <!-- <button id="punchOutBtn" class="btn btn-sm btn-danger float-end" style="margin-left: 25px;">Punch Out</button>
                                <div style="margin-left: 25px;">Out: <span id="outTime">{{ $punches && $punches->punch_out ? \Carbon\Carbon::parse($punches->punch_out)->format('H:i') : '00:00' }}</span></div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Calender view -->
            <!-- <div class=" card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <button id="prevMonth" class="btn btn-outline-primary btn-sm">Previous</button>
                        <h5 id="calendarTitle" class="mb-0"></h5>
                        <button id="nextMonth" class="btn btn-outline-primary btn-sm">Next</button>
                    </div>

                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                                <th>{{ $day }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody id="calendarBody">
            </tbody>
            </table>
        </div>
    </div> -->
            <!-- <div class="card m-2">
                <div class="card-body">
                    <div class="col">
                        <div class="row" style="padding: 2px;">
                            <button class="btn btn-success btn-sm"></button>&nbsp; Present
                        </div>
                        <div class="row" style="padding: 2px;">
                            <button class="btn btn-warning btn-sm"></button>&nbsp; Half Day
                        </div>
                        <div class="row" style="padding: 2px;">
                            <button class="btn btn-danger btn-sm"></button>&nbsp; Leave
                        </div>
                        <br>
                        <div class="text-primary">
                            <strong> Select Date:</strong><br>
                            In Time:
                            <div id="intime">
                                {{ $punches && $punches->punch_in ? \Carbon\Carbon::parse($punches->punch_in)->format('H:i') : '00:00' }}
                            </div>
                            <br>
                            Out Time:
                            <div id="outtime">
                                {{ $punches && $punches->punch_out ? \Carbon\Carbon::parse($punches->punch_out)->format('H:i') : '00:00' }}
                            </div>
                            <br>
                            Total Hours:
                            <div id="total">

                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

        </div>
    </div><!-- container -->
</div><!-- Page content Wrapper -->
</div><!-- content -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentDate = new Date();
        let selectedCell = null;

        function renderCalendar(date) {
            const year = date.getFullYear();
            const month = date.getMonth();
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const startDay = firstDay.getDay();
            const totalDays = lastDay.getDate();

            const title = date.toLocaleString('default', {
                month: 'long',
                year: 'numeric'
            });
            document.getElementById('calendarTitle').textContent = title;

            // Fetch attendance status for the month
            fetch("{{ route('attendance.monthStatus') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        year: year,
                        month: month + 1
                    })
                })
                .then(response => response.json())
                .then(statusMap => {
                    let html = '<tr>';
                    for (let i = 0; i < startDay; i++) {
                        html += '<td></td>';
                    }

                    for (let day = 1; day <= totalDays; day++) {
                        const fullDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                        const status = statusMap[fullDate] || '';
                        let colorClass = '';
                        if (status === 'success') {
                            colorClass = 'bg-success text-white';
                        } else if (status === 'warning') {
                            colorClass = 'bg-warning text-dark';
                        } else if (status === 'danger') {
                            colorClass = 'bg-danger text-white';
                        }

                        if ((startDay + day - 1) % 7 === 0 && day !== 1) {
                            html += '</tr><tr>';
                        }

                        html += `<td class="calendar-cell ${colorClass}" data-date="${fullDate}">${day}</td>`;
                    }

                    html += '</tr>';
                    document.getElementById('calendarBody').innerHTML = html;

                    // Bind click events
                    document.querySelectorAll('.calendar-cell').forEach(cell => {
                        cell.addEventListener('click', function() {
                            // Remove highlight from previously selected cell
                            if (selectedCell) {
                                selectedCell.classList.remove('bg-primary', 'text-white');
                                selectedCell.classList.add(selectedCell.dataset.originalBg || '');
                            }

                            // Store original background class
                            const bgClass = [...this.classList].find(cls => cls.startsWith('bg-') && cls !== 'bg-primary');
                            this.dataset.originalBg = bgClass;

                            // Remove existing bg-* class
                            if (bgClass) this.classList.remove(bgClass);

                            // Apply primary highlight
                            this.classList.add('bg-primary', 'text-white');

                            selectedCell = this;

                            const selectedDate = this.getAttribute('data-date');
                            fetchPunchTimes(selectedDate);
                        });
                    });

                });
        }

        function fetchPunchTimes(date) {
            fetch("{{ route('attendance.getPunchTimes') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        date: date
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const inTime = typeof data.in === 'string' ? data.in : '00:00';
                    const outTime = typeof data.out === 'string' ? data.out : '00:00';

                    document.getElementById('intime').textContent = inTime;
                    document.getElementById('outtime').textContent = outTime;

                    calculateTotalHours(inTime, outTime);
                });
        }

        function sendPunch(action) {
            fetch("{{ route('attendance.punch') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        action: action
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (action === 'in') {
                        document.getElementById('inTime').textContent = data.time || '00:00';
                    } else {
                        document.getElementById('outTime').textContent = data.time || '00:00';
                    }

                    // Optional: show flash message
                    if (data.message) {
                        showAjaxFlash(data.message, 'success');
                    }

                    // Refresh calendar to reflect updated status
                    renderCalendar(currentDate);
                });
        }

        document.getElementById('punchInBtn').addEventListener('click', function() {
            sendPunch('in');
        });

        document.getElementById('punchOutBtn').addEventListener('click', function() {
            sendPunch('out');
        });

        // Optional flash message handler
        function showAjaxFlash(message, type = 'success') {
            const container = document.getElementById('ajax-flash-container');
            container.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        `;
            setTimeout(() => container.innerHTML = '', 3000);
        }

        document.getElementById('prevMonth').addEventListener('click', function() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar(currentDate);
        });

        document.getElementById('nextMonth').addEventListener('click', function() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar(currentDate);
        });

        renderCalendar(currentDate);

        function calculateTotalHours(inTime, outTime) {
            if (!inTime || !outTime || inTime === '00:00' || outTime === '00:00') {
                document.getElementById('total').textContent = '0.00';
                return;
            }

            const [inHour, inMinute] = inTime.split(':').map(Number);
            const [outHour, outMinute] = outTime.split(':').map(Number);

            const inDate = new Date(0, 0, 0, inHour, inMinute);
            const outDate = new Date(0, 0, 0, outHour, outMinute);

            let diffMs = outDate - inDate;
            if (diffMs < 0) diffMs += 24 * 60 * 60 * 1000; // handle overnight shifts

            const diffHours = (diffMs / (1000 * 60 * 60)).toFixed(2);
            document.getElementById('total').textContent = diffHours;
        }

    });
</script>



@endsection('content')