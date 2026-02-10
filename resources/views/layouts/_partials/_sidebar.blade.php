<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <div class="user-details">
            <div class="pull-left">
                <img src="{{ asset('template/images/users/avatar-1.jpg') }}" alt="" class="thumb-md img-circle" />
            </div>
            <div class="user-info">
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                        aria-expanded="false">{{ auth()->user()->nama_pic }}
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @if (auth()->user()->role_user === 'administrator')
                            <li>
                                <a href="#"><i class="md md-settings"></i> Telescope</a>
                            </li>
                        @endif
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
                <p class="text-muted m-0">{{ auth()->user()->role_user }}</p>
            </div>
        </div>
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>
                <li>
                    <a href="{{ route('home') }}" class="waves-effect"><i class="md md-home"></i><span>
                            Dashboard</span></a>
                </li>
                @if (in_array(auth()->user()->role_user, ['approver', 'administrator']))
                    <li class="has_sub">
                        <a href="#" class="waves-effect"><i class="md md-apps"></i><span>
                                Data Master<i class="md md-add pull-right"></i></span></a>
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
                                <a href="{{ route('jenis-pekerjaan.index') }}" class="waves-effect"
                                    style="padding: 10px 25px 10px 30px;">> <span>Jenis Pekerjaan</span></a>
                            </li>
                            <li>
                                <a href="{{ route('pendidikan.index') }}" class="waves-effect"
                                    style="padding: 10px 25px 10px 30px;">> <span>Pendidikan</span></a>
                            </li>
                            <li>
                                <a href="{{ route('status-pernikahan.index') }}" class="waves-effect"
                                    style="padding: 10px 25px 10px 30px;">>
                                    <span>Status Pernikahan</span></a>
                            </li>
                            <li>
                                <a href="{{ route('agama.index') }}" class="waves-effect"
                                    style="padding: 10px 25px 10px 30px;">>
                                    <span>Agama</span></a>
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
                                <a href="{{ route('master-data-bank.index') }}" class="waves-effect"
                                    style="padding: 10px 25px 10px 30px;">>
                                    <span>Master Data Bank</span></a>
                            </li>
                            <li>
                                <a href="{{ route('log-jadwal-pembukaan.index') }}" class="waves-effect"
                                    style="padding: 10px 25px 10px 30px;">>
                                    <span>Log Jadwal Pembukaan</span></a>
                            </li>
                            <li>
                                <a href="{{ route('log-masa-sanggah.index') }}" class="waves-effect"
                                    style="padding: 10px 25px 10px 30px;">>
                                    <span>Log Masa Sanggah</span></a>
                            </li>
                            <li>
                                <a href="{{ route('jenis-dokumen.index') }}" class="waves-effect"
                                    style="padding: 10px 25px 10px 30px;">>
                                    <span>Jenis Dokumen</span></a>
                            </li>
                            <li>
                                <a href="{{ route('master-indikator.index') }}" class="waves-effect"
                                    style="padding: 10px 25px 10px 30px;">>
                                    <span>Master Indikator</span></a>
                            </li>
                            <li>
                                <a href="{{ route('master-data-indikator-laporan.index') }}" class="waves-effect"
                                    style="padding: 10px 25px 10px 30px;">>
                                    <span>Master Data Indikator Laporan</span></a>
                            </li>

                        </ul>
                    </li>
                @endif
                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="md md-group"></i>
                        <span>
                            Data User
                            <i class="md md-add pull-right"></i>
                        </span>
                    </a>
                    <ul class="list-unstyled">
                        <li>
                            <a href="{{ route('pic-kelompok-masyarakat.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">> <span>PIC Kelompok Masyarakat</span></a>
                        </li>
                        <li>
                            <a href="{{ route('user-akseslh.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">>
                                <span>User Akseslh</span></a>
                        </li>
                    </ul>
                </li>
                @if (in_array(auth()->user()->role_user, ['administrator', 'approver']))
                    <li>
                        <a href="{{ route('tolak-pengajuan-dan-profil.index') }}" class="waves-effect"><i
                                class="md md-assignment-late"></i>
                            <span class="text-small">Kelola Penolakan</span>
                        </a>
                    </li>
                @endif
                @if (in_array(auth()->user()->role_user, ['administrator', 'pmu-bpdlh']))
                    <li class="has_sub">
                        <a href="#" class="waves-effect"><i class="md md-attach-money"></i>
                            <span>
                                Penyaluran Dana
                                <i class="md md-add pull-right"></i>
                            </span>
                        </a>
                        <ul class="list-unstyled">
                            <li>
                                <a href="{{ route('transaksi-penyaluran.index') }}" class="">Transaksi
                                    Penyaluran</a>
                            </li>
                            <li>
                                <a href="{{ route('transaksi-penyaluran.import-view') }}" class="">Import
                                    Penyaluran</a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (in_array(auth()->user()->role_user, ['administrator']))
                    <li class="has_sub">
                        <a href="#" class="waves-effect"><i class="md md-mail"></i>
                            <span>
                                Email Blast
                                <i class="md md-add pull-right"></i>
                            </span>
                        </a>
                        <ul class="list-unstyled">
                            <li>
                                <a href="{{ route('email-blast.index') }}" class="">Status Email Blast</a>
                            </li>
                            <li>
                                <a href="#" class="">Email Template</a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (in_array(auth()->user()->role_user, ['administrator']))
                    <li>
                        <a href="{{ route('banner-informasi.index') }}" class="waves-effect"><i
                                class="md md-now-widgets"></i><span> Banner Informasi
                            </span></a>
                    </li>
                    <li>
                        <a href="{{ route('video.index') }}" class="waves-effect"><i
                                class="md md-video-collection"></i><span> Video
                            </span></a>
                    </li>
                    <li>
                        <a href="{{ route('testimonial.index') }}" class="waves-effect"><i
                                class="md md-textsms"></i><span> Testimonial
                            </span></a>
                    </li>
                @endif
                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="md md-view-list"></i><span> Pengajuan Kegiatan<i
                                class="md md-add pull-right"></i>
                        </span>
                    </a>
                    <ul class="list-unstyled">
                        <li>
                            <a href="{{ route('pengajuan-kegiatan.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">> <span>List Pengajuan</span></a>
                        </li>
                        @if (in_array(auth()->user()->role_user, ['administrator', 'approver']))
                            <li>
                                <a href="{{ route('laporan-akhir-kegiatan.index') }}" class="waves-effect"
                                    style="padding: 10px 25px 10px 30px;">> <span>
                                        Unggah Laporan Akhir</span></a>
                            </li>
                            <li>
                                <a href="{{ route('laporan-akhir-kegiatan.edit') }}" class="waves-effect"
                                    style="padding: 10px 25px 10px 30px;">> <span>
                                        Ubah Laporan Akhir</span></a>
                            </li>
                        @endif
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="md md-account-balance-wallet"></i><span> Kelola
                            Verifikasi<i class="md md-add pull-right"></i>
                        </span>
                    </a>
                    <ul class="list-unstyled">
                        <li>
                            <a href="{{ route('profile-pic.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">> <span>Verifikasi Profile PIC</span></a>
                        </li>
                        <li>
                            <a href="{{ route('verifikasi-pengajuan-kegiatan.index') }}" class="waves-effect"
                                style="padding: 10px 25px 10px 30px;">> <span>Verifikasi Administrasi
                                    Pengajuan Kegiatan</span></a>
                        </li>
                    </ul>
                </li>

            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
