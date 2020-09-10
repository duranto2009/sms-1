<?php

namespace App\Imports;

use App\User;
use App\Models\Student;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToModel, WithHeadingRow
{
    public function __construct($class,$section,$session)
    {
        $this->class   = $class;
        $this->section = $section;
        $this->session = $session;
    }
    public function model(array $row)
    {
        $user = User::create([
                    'name'              => $row['name'],
                    'email'             => $row['email'],
                    'password'          => bcrypt($row['password']),
                    'role'              => 'student',
                    'email_verified_at' => now(),
                    'remember_token'    => Str::random(64)
                ]);
        return new Student([
            'name'           => $row['name'],
            'email'          => $row['email'],
            'student_id'     => $this->session.$row['guardian_id'].rand(1111, 9999),
            'user_id'        => $user->id,
            'guardian_id'    => $row['guardian_id'],
            'class_table_id' => $this->class,
            'section'        => $this->section,
            'session'        => $this->session,
            'gender'         => $row['gender'],
        ]);
    }
}
