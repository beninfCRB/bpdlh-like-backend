@extends('layouts.app')

@section('title', 'Edit Data PIC Kelompok Masyarakat')

@section('script')
    <script>
        jQuery("#provinsi_pic").select2({
            width: "100%",
        });
        jQuery("#kabupaten_pic").select2({
            width: "100%",
        });
        jQuery("#kecamatan_pic").select2({
            width: "100%",
        });
        jQuery("#kelurahan_pic").select2({
            width: "100%",
        });
    </script>
    <script src="{{ asset('app/build/pic_kelompok_masyarakat.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <div class="wraper container">
        <div class="row">
            <div class="col-sm-12">
                <div class="bg-picture text-center" style="background-image: url('{{ asset('images/big/bg.jpg') }}')">
                    <div class="bg-picture-overlay"></div>
                    <div class="profile-info-name">
                        <img src="{{ asset('images/avatar-1.jpg') }}" class="thumb-lg img-circle img-thumbnail"
                            alt="profile-image" />
                        <h3 class="text-white">{{ old('nama_pic', $data->nama_pic) }}</h3>
                    </div>
                </div>
                <!--/ meta -->
            </div>
        </div>
        <div class="row user-tabs">
            <div class="col-lg-6 col-md-9 col-sm-9">
                <ul class="nav nav-tabs tabs">
                    <li class="active tab">
                        <a href="#home-2" data-toggle="tab" aria-expanded="false" class="active">
                            <span class="visible-xs"><i class="fa fa-home"></i></span>
                            <span class="hidden-xs">About Me</span>
                        </a>
                    </li>
                    <li class="tab">
                        <a href="#profile-2" data-toggle="tab" aria-expanded="false">
                            <span class="visible-xs"><i class="fa fa-user"></i></span>
                            <span class="hidden-xs">Activities</span>
                        </a>
                    </li>
                    <li class="tab">
                        <a href="#messages-2" data-toggle="tab" aria-expanded="true">
                            <span class="visible-xs"><i class="fa fa-envelope-o"></i></span>
                            <span class="hidden-xs">Projects</span>
                        </a>
                    </li>
                    <li class="tab">
                        <a href="#settings-2" data-toggle="tab" aria-expanded="false">
                            <span class="visible-xs"><i class="fa fa-cog"></i></span>
                            <span class="hidden-xs">Settings</span>
                        </a>
                    </li>
                    <div class="indicator"></div>
                </ul>
            </div>
            <div class="col-lg-6 col-md-3 col-sm-3 hidden-xs">
                <div class="pull-right">
                    <div class="dropdown">
                        <a data-toggle="dropdown"
                            class="dropdown-toggle btn-rounded btn btn-primary waves-effect waves-light" href="#">
                            Following <span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            <li><a href="#">Poke</a></li>
                            <li><a href="#">Private message</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Unfollow</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="tab-content profile-tab-content">
                    <div class="tab-pane active" id="home-2">
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Personal-Information -->
                                <div class="panel panel-default panel-fill">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Personal Information</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="about-info-p">
                                            <strong>Full Name</strong>
                                            <br />
                                            <p class="text-muted">Johnathan Deo</p>
                                        </div>
                                        <div class="about-info-p">
                                            <strong>Mobile</strong>
                                            <br />
                                            <p class="text-muted">(123) 123 1234</p>
                                        </div>
                                        <div class="about-info-p">
                                            <strong>Email</strong>
                                            <br />
                                            <p class="text-muted">
                                                johnathandeon @moltran.com
                                            </p>
                                        </div>
                                        <div class="about-info-p m-b-0">
                                            <strong>Location</strong>
                                            <br />
                                            <p class="text-muted">USA</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Personal-Information -->

                                <!-- Languages -->
                                <div class="panel panel-default panel-fill">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Languages</h3>
                                    </div>
                                    <div class="panel-body">
                                        <ul>
                                            <li>English</li>
                                            <li>Franch</li>
                                            <li>Greek</li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- Languages -->
                            </div>

                            <div class="col-md-8">
                                <!-- Personal-Information -->
                                <div class="panel panel-default panel-fill">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Biography</h3>
                                    </div>
                                    <div class="panel-body">
                                        <p>
                                            Lorem Ipsum is simply dummy text of the printing
                                            and typesetting industry. Lorem Ipsum has been the
                                            industry's standard dummy text ever since the
                                            1500s, when an unknown printer took a galley of
                                            type and scrambled it to make a type specimen
                                            book. It has survived not only five centuries, but
                                            also the leap into electronic typesetting,
                                            remaining essentially unchanged.
                                        </p>

                                        <p>
                                            <strong>But also the leap into electronic typesetting,
                                                remaining essentially unchanged.</strong>
                                        </p>

                                        <p>
                                            It was popularised in the 1960s with the release
                                            of Letraset sheets containing Lorem Ipsum
                                            passages, and more recently with desktop
                                            publishing software like Aldus PageMaker including
                                            versions of Lorem Ipsum.
                                        </p>
                                    </div>
                                </div>
                                <!-- Personal-Information -->

                                <div class="panel panel-default panel-fill">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Skills</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="m-b-15">
                                            <h5>
                                                Angular Js <span class="pull-right">60%</span>
                                            </h5>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-primary wow animated progress-animated"
                                                    role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                                    aria-valuemax="100" style="width: 60%">
                                                    <span class="sr-only">60% Complete</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="m-b-15">
                                            <h5>
                                                Javascript <span class="pull-right">90%</span>
                                            </h5>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-pink wow animated progress-animated"
                                                    role="progressbar" aria-valuenow="90" aria-valuemin="0"
                                                    aria-valuemax="100" style="width: 90%">
                                                    <span class="sr-only">90% Complete</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="m-b-15">
                                            <h5>
                                                Wordpress <span class="pull-right">80%</span>
                                            </h5>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-purple wow animated progress-animated"
                                                    role="progressbar" aria-valuenow="80" aria-valuemin="0"
                                                    aria-valuemax="100" style="width: 80%">
                                                    <span class="sr-only">80% Complete</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="m-b-0">
                                            <h5>
                                                HTML5 &amp; CSS3
                                                <span class="pull-right">95%</span>
                                            </h5>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-info wow animated progress-animated"
                                                    role="progressbar" aria-valuenow="95" aria-valuemin="0"
                                                    aria-valuemax="100" style="width: 95%">
                                                    <span class="sr-only">95% Complete</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="profile-2">
                        <!-- Personal-Information -->
                        <div class="panel panel-default panel-fill">
                            <div class="panel-body">
                                <div class="timeline-2">
                                    <div class="time-item">
                                        <div class="item-info">
                                            <div class="text-muted">5 minutes ago</div>
                                            <p>
                                                <strong><a href="#" class="text-info">John Doe</a></strong>
                                                Uploaded a photo
                                                <strong>"DSC000586.jpg"</strong>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="time-item">
                                        <div class="item-info">
                                            <div class="text-muted">30 minutes ago</div>
                                            <p>
                                                <a href="" class="text-info">Lorem</a> commented
                                                your post.
                                            </p>
                                            <p>
                                                <em>"Lorem ipsum dolor sit amet, consectetur
                                                    adipiscing elit. Aliquam laoreet tellus ut
                                                    tincidunt euismod. "</em>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="time-item">
                                        <div class="item-info">
                                            <div class="text-muted">59 minutes ago</div>
                                            <p>
                                                <a href="" class="text-info">Jessi</a> attended
                                                a meeting with<a href="#" class="text-success">John Doe</a>.
                                            </p>
                                            <p>
                                                <em>"Lorem ipsum dolor sit amet, consectetur
                                                    adipiscing elit. Aliquam laoreet tellus ut
                                                    tincidunt euismod. "</em>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="time-item">
                                        <div class="item-info">
                                            <div class="text-muted">5 minutes ago</div>
                                            <p>
                                                <strong><a href="#" class="text-info">John Doe</a></strong>Uploaded
                                                2 new photos
                                            </p>
                                        </div>
                                    </div>

                                    <div class="time-item">
                                        <div class="item-info">
                                            <div class="text-muted">30 minutes ago</div>
                                            <p>
                                                <a href="" class="text-info">Lorem</a> commented
                                                your post.
                                            </p>
                                            <p>
                                                <em>"Lorem ipsum dolor sit amet, consectetur
                                                    adipiscing elit. Aliquam laoreet tellus ut
                                                    tincidunt euismod. "</em>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="time-item">
                                        <div class="item-info">
                                            <div class="text-muted">59 minutes ago</div>
                                            <p>
                                                <a href="" class="text-info">Jessi</a> attended
                                                a meeting with<a href="#" class="text-success">John Doe</a>.
                                            </p>
                                            <p>
                                                <em>"Lorem ipsum dolor sit amet, consectetur
                                                    adipiscing elit. Aliquam laoreet tellus ut
                                                    tincidunt euismod. "</em>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Personal-Information -->
                    </div>

                    <div class="tab-pane" id="messages-2">
                        <!-- Personal-Information -->
                        <div class="panel panel-default panel-fill">
                            <div class="panel-heading">
                                <h3 class="panel-title">My Projects</h3>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Project Name</th>
                                                <th>Start Date</th>
                                                <th>Due Date</th>
                                                <th>Status</th>
                                                <th>Assign</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Moltran Admin</td>
                                                <td>01/01/2015</td>
                                                <td>07/05/2015</td>
                                                <td>
                                                    <span class="label label-info">Work in Progress</span>
                                                </td>
                                                <td>Coderthemes</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Moltran Frontend</td>
                                                <td>01/01/2015</td>
                                                <td>07/05/2015</td>
                                                <td>
                                                    <span class="label label-success">Pending</span>
                                                </td>
                                                <td>Coderthemes</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Moltran Admin</td>
                                                <td>01/01/2015</td>
                                                <td>07/05/2015</td>
                                                <td>
                                                    <span class="label label-pink">Done</span>
                                                </td>
                                                <td>Coderthemes</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Moltran Frontend</td>
                                                <td>01/01/2015</td>
                                                <td>07/05/2015</td>
                                                <td>
                                                    <span class="label label-purple">Work in Progress</span>
                                                </td>
                                                <td>Coderthemes</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Moltran Admin</td>
                                                <td>01/01/2015</td>
                                                <td>07/05/2015</td>
                                                <td>
                                                    <span class="label label-warning">Coming soon</span>
                                                </td>
                                                <td>Coderthemes</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Personal-Information -->
                    </div>

                    <div class="tab-pane" id="settings-2">
                        <!-- Personal-Information -->
                        <div class="panel panel-default panel-fill">
                            <div class="panel-heading">
                                <h3 class="panel-title">Edit Profile</h3>
                            </div>
                            <div class="panel-body">
                                <form role="form">
                                    <div class="form-group">
                                        <label for="FullName">Full Name</label>
                                        <input type="text" value="John Doe" id="FullName" class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label for="Email">Email</label>
                                        <input type="email" value="first.last@example.com" id="Email"
                                            class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label for="Username">Username</label>
                                        <input type="text" value="john" id="Username" class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label for="Password">Password</label>
                                        <input type="password" placeholder="6 - 15 Characters" id="Password"
                                            class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label for="RePassword">Re-Password</label>
                                        <input type="password" placeholder="6 - 15 Characters" id="RePassword"
                                            class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label for="AboutMe">About Me</label>
                                        <textarea style="height: 125px" id="AboutMe" class="form-control">
Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</textarea
                            >
                          </div>
                          <button
                            class="btn btn-primary waves-effect waves-light w-md"
                            type="submit">
                            Save
                          </button>
                        </form>
                      </div>
                    </div>
                    <!-- Personal-Information -->
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- container -->
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="pull-left page-title">KELOLA DATA PIC KELOMPOK MASYARAKAT</h4>
            <ol class="breadcrumb pull-right">
                <li><a href="#">Data Master</a></li>
                <li><a href="#">Forms</a></li>
                <li class="active">Pengelolaan Data PIC Kelompok Masyarakat</li>
            </ol>
        </div>
    </div>


    <div class="row">
        <!-- Basic example -->
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Pengelolaan Data PIC Kelompok Masyaakat</h3>
                    <input type="hidden" name="app_url" id="app_url" value="{{ env('APP_URL') }}">
                </div>
                <div class="panel-body">
                    <form role="form" action="{{ route('pic-kelompok-masyarakat.update', $data->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="form-group @error('kelompok_masyarakat_id') has-error @enderror col-md-4">
                                <label for="kelompok_masyarakat_id">Kelompok Masyarakat <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" required id="kelompok_masyarakat_id"
                                    name="kelompok_masyarakat_id" required>
                                    <option class='form-control' value=''>- Pilih Data -</option>
                                    @isset($kelompokMasyarakat)
@foreach ($kelompokMasyarakat as $item)
@if (old('kelompok_masyarakat_id', $data->kelompok_masyarakat_id) == $item['id'])
<option class='form-control' value="{{ $item['id'] }}" selected>
                                                    {{ $item['kelompok_masyarakat'] }}</option>
@else
<option class='form-control' value="{{ $item['id'] }}">
                                                    {{ $item['kelompok_masyarakat'] }}</option>
@endif
@endforeach
@endisset
                                </select>
                                @error('kelompok_masyarakat_id')
{{ $message }}
@enderror
                            </div>
                            <div class="form-group @error('nama_pic') has-error @enderror col-md-4">
                                <label for="nama_pic">Nama Lengkap PIC<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_pic" name="nama_pic"
                                    placeholder="Nama Lengkap Tanpa Gelar" value="{{ old('nama_pic', $data->nama_pic) }}">
                                @error('nama_pic')
{{ $message }}
@enderror
                            </div>
                            <div class=" form-group @error('email_pic') has-error @enderror col-md-4">
                                <label for="email_pic">Alamat E-Mail PIC </label>
                                <input type="email" class="form-control" id="email_pic" name="email_pic"
                                    placeholder="Alamat E-Mail" value="{{ old('email_pic', $data->email_pic) }}">
                                @error('email_pic')
{{ $message }}
@enderror
                            </div>
                            <div class="form-group @error('jenis_identitas_pic') has-error @enderror col-md-4">
                                <label for="jenis_identitas_pic">Jenis Identitas PIC<span
                                        class="text-danger">*</span></label>
                                <select class="form-control" required id="jenis_identitas_pic" name="jenis_identitas_pic"
                                    required>
                                    <option class='form-control' value=''>- Pilih Data -</option>
                                    <option class='form-control' value='KTP'
                                        {{ old('jenis_identitas_pic', $data->jenis_identitas_pic) == 'KTP' ? 'selected' : '' }}>
                                        KTP
                                    </option>
                                    <option class='form-control' value='SIM'
                                        {{ old('jenis_identitas_pic', $data->jenis_identitas_pic) == 'SIM' ? 'selected' : '' }}>
                                        SIM
                                    </option>
                                    <option class='form-control' value='Kartu Mahasiswa'
                                        {{ old('jenis_identitas_pic', $data->jenis_identitas_pic) == 'Kartu Mahasiswa' ? 'selected' : '' }}>
                                        Kartu
                                        Mahasiswa</option>
                                </select>
                                @error('jenis_identitas_pic')
{{ $message }}
@enderror
                            </div>
                            <div class="form-group @error('nomor_identitas_pic') has-error @enderror col-md-4">
                                <label for="nomor_identitas_pic">Nomor Identitas PIC<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nomor_identitas_pic"
                                    name="nomor_identitas_pic" placeholder="Nomor Identitas"
                                    value="{{ old('nomor_identitas_pic', $data->nomor_identitas_pic) }}">
                                @error('nomor_identitas_pic')
{{ $message }}
@enderror
                            </div>
                            <div class=" form-group @error('nohp_pic') has-error @enderror col-md-4">
                                <label for="nohp_pic">Nomor HP PIC<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nohp_pic" name="nohp_pic"
                                    placeholder="Contoh: 08123234345" value="{{ old('nohp_pic', $data->nohp_pic) }}">
                                @error('nohp_pic')
{{ $message }}
@enderror
                            </div>
                            <div class=" form-group @error('alamat_pic') has-error @enderror col-md-12">
                                <label for="alamat_pic">Alamat PIC <span class="text-danger">*</span></label>
                                <textarea name="alamat_pic" id="alamat_pic" cols="30" rows="10" class="form-control">
                                                {{ old('alamat_pic', $data->alamat_pic) }}
                                            </textarea>
                                        @error('alamat_pic')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class=" form-group @error('provinsi_pic') has-error @enderror col-md-3">
                                        <label for="provinsi_pic">Provinsi PIC <span class="text-danger">*</span></label>
                                        <select class="" id="provinsi_pic" name="provinsi_pic" required
                                            onchange="getKotaKabupaten()">
                                            <option class='form-control' value=''>- Pilih Data -</option>
                                            @isset($provinsi)
                                                @foreach ($provinsi as $item)
                                                    @if (old('provinsi_pic', $data->provinsi_pic) == $item['id'])
                                                        <option class='form-control' value="{{ $item['id'] }}" selected>
                                                            {{ $item['name'] }}</option>
                                                    @else
                                                        <option class='form-control' value="{{ $item['id'] }}">
                                                            {{ $item['name'] }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endisset
                                        </select>
                                        @error('provinsi_pic')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class=" form-group @error('kabupaten_pic') has-error @enderror col-md-3">
                                        <label for="kabupaten_pic">Kabupaten PIC <span
                                                class="text-danger">*</span></label>
                                        <select class="" id="kabupaten_pic" name="kabupaten_pic" required
                                            onchange="getKecamatan()">
                                            <option class='form-control' value=''>- Pilih Data -</option>
                                            @isset($kota)
                                                @foreach ($kota as $item)
                                                    @if (old('kabupaten_pic', $data->kabupaten_pic) == $item['id'])
                                                        <option class='form-control' value="{{ $item['id'] }}" selected>
                                                            {{ $item['name'] }}</option>
                                                    @else
                                                        <option class='form-control' value="{{ $item['id'] }}">
                                                            {{ $item['name'] }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endisset
                                        </select>
                                        @error('kabupaten_pic')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class=" form-group @error('kecamatan_pic') has-error @enderror col-md-3">
                                        <label for="kecamatan_pic">Kecamatan PIC <span
                                                class="text-danger">*</span></label>
                                        <select class="" id="kecamatan_pic" name="kecamatan_pic" required
                                            onchange="getKelurahan()">
                                            <option class='form-control' value=''>- Pilih Data -</option>
                                            @isset($kecamatan)
                                                @foreach ($kecamatan as $item)
                                                    @if (old('kecamatan_pic', $data->kecamatan_pic) == $item['id'])
                                                        <option class='form-control' value="{{ $item['id'] }}" selected>
                                                            {{ $item['name'] }}</option>
                                                    @else
                                                        <option class='form-control' value="{{ $item['id'] }}">
                                                            {{ $item['name'] }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endisset
                                        </select>
                                        @error('kecamatan_pic')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class=" form-group @error('kelurahan_pic') has-error @enderror col-md-3">
                                        <label for="kelurahan_pic">Kelurahan PIC <span
                                                class="text-danger">*</span></label>
                                        <select class="" id="kelurahan_pic" name="kelurahan_pic" required>
                                            <option class='form-control' value=''>- Pilih Data -</option>
                                            @isset($kelurahan)
                                                @foreach ($kelurahan as $item)
                                                    @if (old('kelurahan_pic', $data->kelurahan_pic) == $item['id'])
                                                        <option class='form-control' value="{{ $item['id'] }}" selected>
                                                            {{ $item['name'] }}</option>
                                                    @else
                                                        <option class='form-control' value="{{ $item['id'] }}">
                                                            {{ $item['name'] }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endisset
                                        </select>
                                        @error('kelurahan_pic')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    <div class="form-group @error('status_user') has-error @enderror col-md-3">
                                        <label for="status_user">Status User <span class="text-danger">*</span></label>
                                        <select class="form-control" required id="status_user" name="status_user"
                                            required>
                                            <option class='form-control' value=''>- Pilih Data -</option>
                                            <option class='form-control' value='ACTIVE'
                                                {{ old('status_user', $data->user_akseslh->status_user) == 'ACTIVE' ? 'selected' : '' }}>
                                                ACTIVE
                                            </option>
                                            <option class='form-control' value='NON ACTIVE'
                                                {{ old('status_user', $data->user_akseslh->status_user) == 'NON ACTIVE' ? 'selected' : '' }}>
                                                NON ACTIVE
                                            </option>
                                        </select>

                                        @error('status_user')
                                            {{ $message }}
                                        @enderror
                                    </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <button type="submit"
                                        class="btn btn-primary waves-effect waves-light">Simpan</button>
                                    <a href="{{ route('pic-kelompok-masyarakat.index') }}"
                                        class="btn btn-inverse waves-effect waves-light">Kembali</a>
                                </div>
                            </div>
                            </form>
                        </div><!-- panel-body -->
                    </div> <!-- panel -->
                </div> <!-- col-->

            </div> <!-- End row -->
        @endsection
