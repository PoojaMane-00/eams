@extends('layouts.sideheader')
@section('content')
<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="btn-group float-right">
                        <ol class="breadcrumb hide-phone p-0 m-0">
                            <li class="breadcrumb-item"><a href="#">SBMS / </a></li>
                            <h4 class="page-title">Dashboard {{ $userId }}</h4>
                        </ol>
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-primary me-3" onclick="window.history.back();">Back</button>
                    </div>
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
        $attendance = \App\Models\Attendance::where('employee_id', $userId)
        ->whereDate('date', $today)
        ->first();
        @endphp
        <input type="hidden" value="{{ $userId }}" id="userId">

        <div class="row"><!-- Column -->
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="col-3 align-self-center">
                                <div class="round"><i class="mdi mdi-glassdoor"></i></div>
                            </div>
                            <div class="col-9 align-self-center">
                                <button id="punchInBtn" class="btn btn-sm btn-success float-end" style="margin-left: 25px;">Punch In</button>
                                <div style="margin-left: 25px;">In: <span id="inTime">{{ $punches && $punches->punch_in ? \Carbon\Carbon::parse($punches->punch_in)->format('H:i') : '00:00' }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="col-3 align-self-center">
                                <div class="round"><i class="mdi mdi-glassdoor"></i></div>
                            </div>
                            <div class="col-9 align-self-center">
                                <button id="punchOutBtn" class="btn btn-sm btn-danger float-end" style="margin-left: 25px;">Punch Out</button>
                                <div style="margin-left: 25px;">Out: <span id="outTime">{{ $punches && $punches->punch_out ? \Carbon\Carbon::parse($punches->punch_out)->format('H:i') : '00:00' }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Calender view -->
            <div class=" card">
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
                            <!-- JS will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card m-2">
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
                            <strong>Date: <span id="selectedDateSpan"></span></strong><br>

                            In Time:
                            <div id="intime">
                                {{ $punches && $punches->punch_in ? \Carbon\Carbon::parse($punches->punch_in)->format('H:i') : '00:00' }}
                            </div>
                            <input type="time" id="edit-intime" class="form-control form-control-sm mt-1" style="display:none;" />

                            <br>
                            Out Time:
                            <div id="outtime">
                                {{ $punches && $punches->punch_out ? \Carbon\Carbon::parse($punches->punch_out)->format('H:i') : '00:00' }}
                            </div>
                            <input type="time" id="edit-outtime" class="form-control form-control-sm mt-1" style="display:none;" />

                            <br>
                            Total Hours:
                            <div id="total">0</div>
                        </div>

                        <br>
                        <div class="text-end">
                            <button id="edit-btn" class="btn btn-outline-primary btn-sm">
                                <i class="mdi mdi-border-color"></i> Edit
                            </button>
                            <button id="submit-btn" class="btn btn-success btn-sm" style="display:none;">
                                Done
                            </button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div><!-- container -->
</div><!-- Page content Wrapper -->
</div><!-- content -->
<script>
    let userId = document.getElementById('userId').value;
    // console.log(userId);
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
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({
                        year: year,
                        month: month + 1,
                        user_id: userId
                    })
                })
                .then(response => response.json())
                .then(statusMap => {
                    let html = '<tr>';
                    for (let i = 0; i < startDay; i++) {
                        html += '<td></td>';
                    }

                    for (let day = 1; day <= totalDays; day++) {
                        const fullDate = `${year}-${String(month + 1).padStart(2, '0' )}-${String(day).padStart(2, '0' )}`;
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
            selectedDateSpan.textContent = date;
            // alert(date);
            fetch("{{ route('attendance.getPunchTimes') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        date: date,
                        user_id: userId
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
                        action: action,
                        user_id: userId
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inTimeDiv = document.getElementById("intime");
        const outTimeDiv = document.getElementById("outtime");
        const inTimeInput = document.getElementById("edit-intime");
        const outTimeInput = document.getElementById("edit-outtime");
        const totalDisplay = document.getElementById("total");
        const editBtn = document.getElementById("edit-btn");
        const submitBtn = document.getElementById("submit-btn");

        let selectedDate = null;

        function calculateHours() {
            const inTime = inTimeInput.value;
            const outTime = outTimeInput.value;

            if (inTime && outTime) {
                const [inH, inM] = inTime.split(":").map(Number);
                const [outH, outM] = outTime.split(":").map(Number);

                const inMinutes = inH * 60 + inM;
                const outMinutes = outH * 60 + outM;

                let diff = outMinutes - inMinutes;
                if (diff < 0) diff += 1440;

                const hours = (diff / 60).toFixed(2);
                totalDisplay.textContent = hours;
            } else {
                totalDisplay.textContent = "0";
            }
        }

        window.onDateSelected = function(date) {
            selectedDate = date;

            fetch("{{ route('attendance.getPunchByDate') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({
                        date: selectedDate,
                        user_id: user_id
                    })
                })
                .then(res => res.json())
                .then(data => {
                    inTimeDiv.textContent = data.punch_in || "00:00";
                    outTimeDiv.textContent = data.punch_out || "00:00";
                    inTimeInput.value = data.punch_in || "00:00";
                    outTimeInput.value = data.punch_out || "00:00";
                    calculateHours();
                })
                .catch(err => {
                    console.error("Error loading punch data:", err);
                });
        };

        editBtn.addEventListener("click", function() {
            inTimeInput.style.display = "block";
            outTimeInput.style.display = "block";
            inTimeInput.value = inTimeDiv.textContent.trim();
            outTimeInput.value = outTimeDiv.textContent.trim();
            submitBtn.style.display = "inline-block";
            editBtn.style.display = "none";
        });

        submitBtn.addEventListener("click", function() {
            const inTime = inTimeInput.value;
            const outTime = outTimeInput.value;
            const selectedDate = selectedDateSpan.textContent.trim();
            // alert(selectedDate);

            fetch("{{ route('attendance.updatePunch') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({
                        punch_in: inTime,
                        punch_out: outTime,
                        date: selectedDate,
                        user_id: userId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alert("Time updated successfully!");
                    window.location.reload();
                    inTimeDiv.textContent = inTime;
                    outTimeDiv.textContent = outTime;
                    inTimeInput.style.display = "none";
                    outTimeInput.style.display = "none";
                    submitBtn.style.display = "none";
                    editBtn.style.display = "inline-block";
                    calculateHours();
                })
                .catch(err => {
                    alert("Error updating punch.");
                    console.error(err);
                });
        });

        inTimeInput.addEventListener("change", calculateHours);
        outTimeInput.addEventListener("change", calculateHours);
    });
</script>




@endsection('content')