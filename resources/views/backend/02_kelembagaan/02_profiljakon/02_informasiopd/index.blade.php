@include('backend.00_administrator.00_baganterpisah.01_header')

<!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
{{-- ---------------------------------------------------------------------- --}}

@include('backend.00_administrator.00_baganterpisah.04_navbar')
{{-- ---------------------------------------------------------------------- --}}

      @include('backend.00_administrator.00_baganterpisah.03_sidebar')

      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">

              <div class="col-sm-12"><h3 class="mb-0">Selamat datang ! <span style="color: black; font-weight:800;" > {{ Auth::user()->name }}</span> di Dashboard <span style="color: black; font-weight:800;"> {{ Auth::user()->statusadmin->statusadmin }} </span>  Sistem Informasi Pembina Jasa Konstruksi Kab Blora</h3></div>

            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>

        <br>
        <!-- Menampilkan pesan sukses -->

        {{-- ======================================================= --}}
        {{-- ALERT --}}

        @include('backend.00_administrator.00_baganterpisah.06_alert')

        {{-- ======================================================= --}}

            <!-- Menyertakan FontAwesome untuk ikon -->

        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row" style="margin-right: 10px; margin-left:10px;">
                <!-- /.card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <div style="
                        margin-bottom:10px;
                        font-weight: 900;
                        font-size: 16px;
                        text-align: center;
                        background: linear-gradient(135deg, #166534, #166534);
                        color: white;
                        padding: 10px 25px;
                        border-radius: 10px;
                        display: inline-block;
                        box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);
                        width: 100%;
                    ">
                        📌 Halaman : {{$title}}
                    </div>

                    <div class="col-md-12">
                        <!--begin::Quick Example-->
                        <div class="card card-primary card-outline mb-6">
                            <!--begin::Header-->
                            {{-- <div class="card-header"><div class="card-title">Quick Example</div></div> --}}
                            <!--end::Header-->
                            <!--begin::Form-->

                            @foreach ($data as $item)

                            <form>
                                <!--begin::Body-->
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Left Column (6/12) -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    <i class="bi bi-house-door" style="margin-right: 8px; color: navy;"></i> Nama Organisasi Perangkat Daerah (OPD)
                                                </label>
                                                <input class="form-control" value="{{$item->namaopd}}" readonly/>
                                                <div class="form-text"></div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">
                                                    <i class="bi bi-house-door" style="margin-right: 8px; color: navy;"></i> Alamat Organisasi Perangkat Daerah (OPD)
                                                </label>
                                                <input class="form-control" value="{{$item->alamatopd}}" readonly />
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">
                                                    <i class="bi bi-house-door" style="margin-right: 8px; color: navy;"></i> RT/RW
                                                </label>
                                                <input class="form-control" value="{{$item->rtrw}}" readonly/>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">
                                                    <i class="bi bi-mailbox" style="margin-right: 8px; color: navy;"></i> Kode Pos
                                                </label>
                                                <input class="form-control" value="{{$item->kodepos}}" readonly/>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">
                                                    <i class="bi bi-map" style="margin-right: 8px; color: navy;"></i> Kelurahan
                                                </label>
                                                <input class="form-control" value="{{$item->kelurahan}}" readonly/>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    <i class="bi bi-geo-alt" style="margin-right: 8px; color: navy;"></i> Kecamatan
                                                </label>
                                                <input class="form-control" value="{{$item->kecamatan}}" readonly/>
                                            </div>


                                        </div>

<!-- Right Column (6/12) -->
<div class="col-md-6">
    <div class="mb-3">
        <label class="form-label">
            <i class="bi bi-building" style="margin-right: 8px; color: navy;"></i> Kota
        </label>
        <input class="form-control" value="{{$item->kota}}" readonly/>
    </div>

    <div class="mb-3">
        <label class="form-label">
            <i class="bi bi-geo-alt" style="margin-right: 8px; color: navy;"></i> Provinsi
        </label>
        <input class="form-control" value="{{$item->provinsi}}" readonly/>
    </div>

    <div class="mb-3">
        <label class="form-label">
            <i class="bi bi-globe" style="margin-right: 8px; color: navy;"></i> Negara
        </label>
        <input class="form-control" value="{{$item->negara}}" readonly/>
    </div>

    <div class="mb-3">
        <label class="form-label">
            <i class="bi bi-map" style="margin-right: 8px; color: navy;"></i> Titik Geografis
        </label>
        <input class="form-control" value="{{$item->posisigeografis}}" readonly/>
    </div>

    <div class="mb-3">
        <label class="form-label">
            <i class="bi bi-briefcase" style="margin-right: 8px; color: navy;"></i> Tipe Dinas
        </label>
        <input class="form-control" value="{{$item->tipedinas}}" readonly/>
    </div>
</div>
<!-- End Right Column -->

                                    </div> <!-- end row -->
                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Quick Example-->

                    </div>
                    <!-- /.card -->
                    <!-- Button Section -->
                    <br><br>
                    <div style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
                        <a href="/beinfoopd/update/{{$item->id}}">
                            <button
                            onmouseover="this.style.backgroundColor='white'; this.style.color='black';"
                            onmouseout="this.style.backgroundColor='#117235'; this.style.color='white';"
                            style="background-color: #117235; color: white; border: none; margin-right: 10px; padding: 10px 20px; border-radius: 15px; font-size: 16px; cursor: pointer; display: flex; align-items: center; transition: background-color 0.3s, color 0.3s; text-decoration: none;">
                            <!-- Ikon Kembali -->
                            <i class="fa fa-file" style="margin-right: 8px;"></i>
                            Update
                        </button>
                        </a>
                        <a href="/beprofiljakon">
                            <button
                            onmouseover="this.style.backgroundColor='white'; this.style.color='black';"
                            onmouseout="this.style.backgroundColor='#374151'; this.style.color='white';"
                            style="background-color: #374151; color: white; border: none; margin-right: 10px; padding: 10px 20px; border-radius: 15px; font-size: 16px; cursor: pointer; display: flex; align-items: center; transition: background-color 0.3s, color 0.3s; text-decoration: none;">
                            <!-- Ikon Kembali -->
                            <i class="fa fa-arrow-left" style="margin-right: 8px;"></i>
                            Kembali
                        </button>
                    </a>
                </div>

                @endforeach
                    </div>
                    <!--end::Row-->
                    </div>

        </div>
        <!--end::Row-->
        </div>
                  <!--end::Container-->
        <!--end::App Content Header-->
        <!--begin::App Content-->
          <!--end::App Content-->
      </main>
      <!--end::App Main-->
    </div>
    </div>


      @include('backend.00_administrator.00_baganterpisah.02_footer')
