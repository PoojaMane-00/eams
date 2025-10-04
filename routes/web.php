<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home;
use App\Http\Controllers\EmployeeController;


Route::get('/', [Home::class, 'index'])->name('login'); // Employee login
Route::get('/admin', [Home::class, 'admin'])->name('admin.login'); // Admin login

Route::resource('employees', Home::class);
Route::get('/employees', [Home::class, 'employees'])->name('employees');
Route::get('/empdashboard', [Home::class, 'empdashboard'])->name('empdashboard');
Route::get('/dashboard', [Home::class, 'dashboard'])->name('dashboard');
Route::get('/profile', [Home::class, 'profile'])->name('profile');
Route::get('/attendance', [Home::class, 'attendance'])->name('attendance');
// Route::get('/punch', [Home::class, 'punch'])->name('attendance.punch');
Route::get('/logout', [Home::class, 'logout'])->name('logout');
Route::get('/forgotpass', [Home::class, 'forgotpass'])->name('forgotpass');

Route::post('/login', [Home::class, 'login'])->name('admin.login.post'); // Admin login POST
Route::match(['get', 'post'], '/', [Home::class, 'index'])->name('login');

Route::post('/employee', [Home::class, 'employee'])->name('employee.post');
Route::post('/login', [Home::class, 'login'])->name('login.post')->middleware('web');
Route::post('/saveprofile', [Home::class, 'saveprofile'])->name('saveprofile');
Route::post('/create', [Home::class, 'create'])->name('create');
Route::post('/punch', [Home::class, 'punch'])->name('attendance.punch');
Route::post('/get-punch-times', [Home::class, 'getPunchTimes'])->name('attendance.getPunchTimes');
Route::post('/month-status', [Home::class, 'getMonthStatus'])->name('attendance.monthStatus');

Route::post('/edit', [Home::class, 'edit'])->name('employee.edit');
Route::get('/view/{userId}', [Home::class, 'view'])->name('employee.view');
Route::get('/slip/{userId?}', [Home::class, 'slip'])->name('slip');
Route::get('/profileview/{userId}', [Home::class, 'profileview'])->name('employee.profileview');

// To fetch punch data for selected date
Route::post('/get-punch-by-date', [Home::class, 'getPunchByDate'])->name('attendance.getPunchByDate');

// To update punch data
Route::post('/update-punch', [Home::class, 'updatePunch'])->name('attendance.updatePunch');
// Route::post('/create-lead', [Home::class, 'createLead'])->name('leads.create');
Route::match(['get', 'post'], '/create-lead', [Home::class, 'createLead'])->name('leads.create');
Route::match(['get', 'post'], '/leads', [Home::class, 'leads'])->name('leads');

// Route::match(['get', 'post', '/employees.destroy', [Home::class], 'deleteEmp'])->name('employees.destroy');
Route::delete('/destroy/{id}', [Home::class, 'deleteEmp'])->name('destroy');
