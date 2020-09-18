<div class="default-sidebar">
    <!-- Begin Side Navbar -->
    <nav class="side-navbar box-scroll sidebar-scroll">
        <!-- Begin Main Navigation -->
        <ul class="list-unstyled">
            <li class="active">
                <a href="#"><i class="la la-columns"></i><span>Dashboard</span></a>
            </li>
        </ul>
        {{-- <span class="heading">Components</span> --}}
        <ul class="list-unstyled">
            <li>
                <a href="#dropdown-app" aria-expanded="false" data-toggle="collapse"><i
                        class="la la-user"></i><span>Users</span></a>
                <ul id="dropdown-app" class="collapse list-unstyled pt-0">
                    <li><a href="{{route('student.index')}}">Student</a></li>
                    <li><a href="{{route('student.create')}}">Admission</a></li>
                    <li><a href="{{route('teacher.index')}}">Teacher</a></li>
                    <li><a href="{{route('teacher.readPermission')}}">Teacher Permission</a></li>
                    <li><a href="{{route('guardian.index')}}">Parent</a></li>
                    <li><a href="{{route('accountant.index')}}">Accountant</a></li>
                    <li><a href="{{route('librarian.index')}}">Librarian</a></li>
                </ul>
            </li>
            <li><a href="#dropdown-ui" aria-expanded="false" data-toggle="collapse"><i
                        class="la la-graduation-cap"></i><span>Academic</span></a>
                <ul id="dropdown-ui" class="collapse list-unstyled pt-0">
                    <li><a href="">Daily Attendance</a></li>
                    <li><a href="">Class Routine</a></li>
                    <li><a href="{{route('subject.index')}}">Subject</a></li>
                    <li><a href="{{route('syllabus.index')}}">Syllabus</a></li>
                    <li><a href="{{route('class.index')}}">Class</a></li>
                    <li><a href="{{route('classroom.index')}}">Class Room</a></li>
                    <li><a href="{{route('department.index')}}">Department</a></li>
                    <li><a href="{{route('session.index')}}">Session</a></li>
                    <li><a href="">Event Calender</a></li>
                </ul>
            </li>
            <li><a href="#dropdown-icons" aria-expanded="false" data-toggle="collapse"><i
                        class="la la-book"></i><span>Exam</span></a>
                <ul id="dropdown-icons" class="collapse list-unstyled pt-0">
                    <li><a href="">Marks</a></li>
                    <li><a href="">Exam</a></li>
                    <li><a href="">Grade</a></li>
                    <li><a href="">Promotion</a></li>
                </ul>
            </li>
            <li><a href="#dropdown-forms" aria-expanded="false" data-toggle="collapse"><i
                        class="la la-bank"></i><span>Accounting</span></a>
                <ul id="dropdown-forms" class="collapse list-unstyled pt-0">
                    <li><a href="">Student Fee Manager</a></li>
                    <li><a href="">Expense Category</a></li>
                    <li><a href="">Expense Manager</a></li>
                </ul>
            </li>
            <li><a href="#dropdown-tables" aria-expanded="false" data-toggle="collapse"><i
                        class="la la-th-large"></i><span>Back Office</span></a>
                <ul id="dropdown-tables" class="collapse list-unstyled pt-0">
                    <li><a href="">Book List Manager</a></li>
                    <li><a href="">Book Issu Report</a></li>
                    <li><a href="">Noticeboard</a></li>
                </ul>
            </li>
            <li><a href="#school-setting" aria-expanded="false" data-toggle="collapse"><i
                        class="la la-gear"></i><span>Setting</span></a>
                <ul id="school-setting" class="collapse list-unstyled pt-0">
                    <li><a href="">School Setting</a></li>
                </ul>
            </li>
        </ul>
        <!-- End Main Navigation -->
    </nav>
    <!-- End Side Navbar -->
</div>
<!-- End Left Sidebar -->
