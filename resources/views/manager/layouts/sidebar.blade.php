<div class="sidebar sidebar-dark bg-dark">
    <ul class="list-unstyled">
        <li class="active"><a href="{{ url('home') }}"><i class="fa fa-fw fa-tachometer-alt"></i> Dashboard</a></li>
        <li id="allbranch"><a href="{{ route('manager.branchlist') }}"><i class="fa fa-fw fa-plus"></i> All Branch</a></li>
        <li>
            <a href="#branchStaff" data-toggle="collapse">
                <i class="fa fa-fw fa-user"></i> Branch Staff Info
            </a>
            <ul id="branchStaff" class="list-unstyled collapse">
                <li><a href="{{ route('branchstaff.create') }}"><i class="far fa-circle"></i> Add New Staff</a></li>
                <li><a href="{{ route('branchstaff.index') }}"><i class="far fa-circle"></i> Manage Staff</a></li>
            </ul>
        </li>
        <li>
            <a href="#currierInfo" data-toggle="collapse">
                <i class="fa fa-fw fas fa-list"></i> Currier Info
            </a>
            <ul id="currierInfo" class="list-unstyled collapse">
                <li><a href="{{ route('departure.manager') }}"><i class="far fa-circle"></i> Departure Currier</a></li>
                <li><a href="{{ route('upcoming.manager') }}"><i class="far fa-circle"></i> Upcoming Currier</a></li>
            </ul>
        </li>
        <li id="branchIncome"><a href="{{ route('manager.branch.income') }}"><i class="fa fa-fw fa-money-bill-alt"></i> Branch Income</a></li>
    </ul>
</div>