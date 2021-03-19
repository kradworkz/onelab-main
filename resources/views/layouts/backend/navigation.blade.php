<div class="topnav">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="/home" id="topnav-dashboard" role="button">
                            <i class="icofont icofont-ui-home mr-2"></i><span key="t-dashboards">Dashboards</span> 
                        </a>
                    </li>

                    @if(Auth::user()->type == 'Administrator')

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="/staffs" id="topnav-dashboard" role="button">
                            <i class="bx bxs-tv mr-2"></i><span key="t-dashboards">Staffs</span> 
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="/dropdownlists" id="topnav-dashboard" role="button">
                            <i class="icofont icofont-listine-dots mr-2"></i><span key="t-dashboards">Dropdown Lists</span> 
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-pages" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-customize mr-2"></i><span key="t-apps">Lists</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-pages">
                            <a href="/agencies" class="dropdown-item" key="t-calendar"><i class="iconfont icofont-bank-alt mr-2"></i>Agencies</a>
                            <a href="/testservices" class="dropdown-item" key="t-chat"><i class="iconfont icofont-test-tube-alt mr-2"></i>Test Services</a>
                            <a href="/packages" class="dropdown-item" key="t-file-manager"><i class="iconfont icofont-package mr-2"></i>Packages</a>
                            <a href="/addons" class="dropdown-item" key="t-file-managers"><i class="iconfont icofont-package mr-2"></i>Add Ons</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="/chatbox" target="_blank" id="topnav-dashboard" role="button">
                            <i class="icofont icofont-wechat mr-2"></i><span key="t-dashboards">Chatbox</span> 
                        </a>
                    </li>

                    @else 
                    
                    @endif
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="/articles" id="topnav-dashboard" role="button">
                            <i class="icofont icofont-newspaper mr-2"></i><span key="t-dashboards">Articles</span> 
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>