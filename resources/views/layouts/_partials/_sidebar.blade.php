<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <div class="user-details">
            <div class="pull-left">
                <img src="{{ asset('images/users/avatar-1.jpg') }}" alt="" class="thumb-md img-circle" />
            </div>
            <div class="user-info">
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                        aria-expanded="false">{{ auth()->user()->nama_pic }}
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="javascript:void(0)"><i class="md md-face-unlock"></i> Profile
                                <div class="ripple-wrapper"></div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><i class="md md-settings"></i> Settings</a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="post" id="logout-form-sidebar">
                                @csrf
                            </form>
                            <a href="#" onclick="document.getElementById('logout-form').submit()"><i
                                    class="md md-settings-power"></i>
                                Logout</a>
                        </li>
                    </ul>
                </div>

                <p class="text-muted m-0">{{ auth()->user()->email }}</p>
            </div>
        </div>
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>
                <li>
                    <a href="{{ route('home') }}" class="waves-effect" style="padding-left: 20px;"><i class="md md-home"
                            style="margin-right: 5px;"></i><span> Dashboard</span></a>
                </li>
                <li class="has_sub">
                    <a href="#" class="waves-effect" style="padding-left: 20px;"><i class="md md-apps"
                            style="margin-right: 5px;"></i><span> Data Master<i
                                class="md md-add pull-right"></i></span></a>
                    <ul class="list-unstyled">
                        <li>
                            <a href="{{ route('jenis-kelompok-masyarakat.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">> <span>Jenis Kelompok Masyarakat</span></a>
                        </li>
                        <li>
                            <a href="{{ route('kelompok-masyarakat.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">> <span>Kelompok Masyarakat</span></a>
                        </li>
                        <li>
                            <a href="{{ route('jenis-kegiatan.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">>
                                <span>Jenis Kegiatan</span></a>
                        </li>
                        <li>
                            <a href="{{ route('tematik-kegiatan.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">> <span>Tematik Kegiatan</span></a>
                        </li>
                        <li>
                            <a href="{{ route('sub-tematik-kegiatan.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">> <span>Sub Tematik Kegiatan</span></a>
                        </li>
                        <li>
                            <a href="{{ route('master-sub-tematik-kegiatan.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">> <span>Master Sub Tematik Kegiatan</span></a>
                        </li>
                        <li>
                            <a href="{{ route('paket-kegiatan.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">>
                                <span>Paket Kegiatan</span></a>
                        </li>
                        <li>
                            <a href="{{ route('pic-kelompok-masyarakat.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">> <span>PIC Kelompok Masyarakat</span></a>
                        </li>
                        <li>
                            <a href="{{ route('tahapan-pengajuan-kegiatan.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">> <span>Tahapan Pengajuan Kegiatan</span></a>
                        </li>
                        <li>
                            <a href="{{ route('satuan.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">>
                                <span>Satuan</span></a>
                        </li>
                        <li>
                            <a href="{{ route('jenis-komponen-rab.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">>
                                <span>Jenis Komponen RAB</span></a>
                        </li>
                        <li>
                            <a href="{{ route('master-komponen-rab.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">>
                                <span>Master Komponen RAB</span></a>
                        </li>
                        <li>
                            <a href="{{ route('pengajuan-kegiatan.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">>
                                <span>Pengajuan Kegiatan</span></a>
                        </li>
                        <li>
                            <a href="{{ route('master-data-bank.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">>
                                <span>Master Data Bank</span></a>
                        </li>
                        <li>
                            <a href="{{ route('user-akseslh.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">>
                                <span>User Akseslh</span></a>
                        </li>
                        <li>
                            <a href="{{ route('log-jadwal-pembukaan.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">>
                                <span>Log Jadwal Pembukaan</span></a>
                        </li>
                        <li>
                            <a href="{{ route('jenis-dokumen.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">>
                                <span>Jenis Dokumen</span></a>
                        </li>

                        <li>
                            <a href="{{ route('transaksi-penyaluran.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">>
                                <span>Transaksi Penyaluran</span></a>
                        </li>
                        <li>
                            <a href="{{ route('master-data-indikator-laporan.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">>
                                <span>Master Data Indikator Laporan</span></a>
                        </li>
                    </ul>
                </li>

                <!-- <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="md md-mail"></i><span> Mail </span><span
                            class="pull-right"><i class="md md-add"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="inbox.html">Inbox</a></li>
                        <li><a href="email-compose.html">Compose Mail</a></li>
                        <li><a href="email-read.html">View Mail</a></li>
                    </ul>
                </li>

                <li>
                    <a href="calendar.html" class="waves-effect"><i class="md md-event"></i><span> Calendar
                        </span></a>
                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="md md-palette"></i> <span> Elements </span>
                        <span class="pull-right"><i class="md md-add"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="typography.html">Typography</a></li>
                        <li><a href="buttons.html">Buttons</a></li>
                        <li><a href="panels.html">Panels</a></li>
                        <li><a href="checkbox-radio.html">Checkboxs-Radios</a></li>
                        <li>
                            <a href="tabs-accordions.html">Tabs &amp; Accordions</a>
                        </li>
                        <li><a href="modals.html">Modals</a></li>
                        <li><a href="bootstrap-ui.html">BS Elements</a></li>
                        <li><a href="progressbars.html">Progress Bars</a></li>
                        <li><a href="notification.html">Notification</a></li>
                        <li><a href="sweet-alert.html">Sweet-Alert</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="md md-invert-colors-on"></i><span> Components
                        </span><span class="pull-right"><i class="md md-add"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="grid.html">Grid</a></li>
                        <li><a href="portlets.html">Portlets</a></li>
                        <li><a href="widgets.html">Widgets</a></li>
                        <li><a href="nestable-list.html">Nesteble</a></li>
                        <li><a href="ui-sliders.html">Sliders </a></li>
                        <li><a href="gallery.html">Gallery </a></li>
                        <li><a href="pricing.html">Pricing Table </a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="md md-redeem"></i> <span> Icons </span>
                        <span class="pull-right"><i class="md md-add"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="material-icon.html">Material Design</a></li>
                        <li><a href="ion-icons.html">Ion Icons</a></li>
                        <li><a href="font-awesome.html">Font awesome</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="md md-now-widgets"></i><span> Forms </span><span
                            class="pull-right"><i class="md md-add"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="form-elements.html">General Elements</a></li>
                        <li><a href="form-validation.html">Form Validation</a></li>
                        <li><a href="form-advanced.html">Advanced Form</a></li>
                        <li><a href="form-wizard.html">Form Wizard</a></li>
                        <li><a href="form-editor.html">WYSIWYG Editor</a></li>
                        <li><a href="code-editor.html">Code Editors</a></li>
                        <li><a href="form-uploads.html">Multiple File Upload</a></li>
                        <li><a href="form-xeditable.html">X-editable</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="md md-view-list"></i><span> Data Tables
                        </span><span class="pull-right"><i class="md md-add"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="tables.html">Basic Tables</a></li>
                        <li><a href="table-datatable.html">Data Table</a></li>
                        <li><a href="tables-editable.html">Editable Table</a></li>
                        <li><a href="responsive-table.html">Responsive Table</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="md md-poll"></i><span> Charts </span><span
                            class="pull-right"><i class="md md-add"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="morris-chart.html">Morris Chart</a></li>
                        <li><a href="chartjs.html">Chartjs</a></li>
                        <li><a href="flot-chart.html">Flot Chart</a></li>
                        <li><a href="peity-chart.html">Peity Charts</a></li>
                        <li><a href="charts-sparkline.html">Sparkline Charts</a></li>
                        <li><a href="chart-radial.html">Radial charts</a></li>
                        <li><a href="other-chart.html">Other Chart</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="md md-place"></i><span> Maps </span><span
                            class="pull-right"><i class="md md-add"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="gmap.html"> Google Map</a></li>
                        <li><a href="vector-map.html"> Vector Map</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect active"><i class="md md-pages"></i><span> Pages </span><span
                            class="pull-right"><i class="md md-add"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="profile.html">Profile</a></li>
                        <li><a href="timeline.html">Timeline</a></li>
                        <li><a href="invoice.html">Invoice</a></li>
                        <li><a href="email-template.html">Email template</a></li>
                        <li><a href="contact.html">Contact-list</a></li>
                        <li><a href="login.html">Login</a></li>
                        <li><a href="register.html">Register</a></li>
                        <li><a href="recoverpw.html">Recover Password</a></li>
                        <li><a href="lock-screen.html">Lock Screen</a></li>
                        <li class="active"><a href="blank.html">Blank Page</a></li>
                        <li><a href="maintenance.html">Maintenance</a></li>
                        <li><a href="coming-soon.html">Coming-soon</a></li>
                        <li><a href="404.html">404 Error</a></li>
                        <li><a href="404_alt.html">404 alt</a></li>
                        <li><a href="500.html">500 Error</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="md md-share"></i><span>Multi
                            Level </span><span class="pull-right"><i class="md md-add"></i></span></a>
                    <ul>
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><span>Menu Level 1.1</span>
                                <span class="pull-right"><i class="md md-add"></i></span></a>
                            <ul style="">
                                <li>
                                    <a href="javascript:void(0);"><span>Menu Level 2.1</span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"><span>Menu Level 2.2</span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"><span>Menu Level 2.3</span></a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);"><span>Menu Level 1.2</span></a>
                        </li>
                    </ul>
                </li> -->
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
