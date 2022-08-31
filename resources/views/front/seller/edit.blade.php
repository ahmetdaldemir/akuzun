<div class="widget-list">
    <div class="row">
        <div class="col-md-12 widget-holder">
            <div class="widget-bg">
                <div class="widget-body clearfix">
                    <form action="{{ route('customers.update', ['id'=>$data[0]['id']]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if($data[0]['photo']!="")
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <img src="{{ asset($data[0]['photo']) }}" style="width: 250px;" alt="" class="img-thumbnail">
                                </div>
                            </div>
                        @endif
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Müşteri Resmi</label>
                                <input class="form-control" name="photo" type="file">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Müşteri Tipi</label>
                                <div>
                                    <input name="customer_type" class="change-customerType" @if($data[0]['customer_type'] == 0) checked @endif type="radio" value="0"> Bireysel
                                </div>
                                <div>
                                    <input name="customer_type" class="change-customerType" @if($data[0]['customer_type'] == 1) checked @endif type="radio" value="1"> Kurumsal
                                </div>
                            </div>
                        </div>

                        <div class="form-group row firma--area" @if($data[0]['customer_type'] == 0) style="display: none;" @endif>
                            <div class="col-md-4">
                                <label class="col-form-label" for="l0">Firma Adı</label>
                                <input class="form-control" name="company_name" type="text" value="{{ $data[0]['company_name'] }}">
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label" for="l0">Vergi Numarası</label>
                                <input class="form-control" name="tax_number" type="text" value="{{ $data[0]['tax_number'] }}">
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label" for="l0">Vergi Dairesi</label>
                                <input class="form-control" name="tax_administration" type="text" value="{{ $data[0]['tax_administration'] }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="col-form-label" for="l0">Ad</label>
                                <input class="form-control" name="name" type="text" value="{{ $data[0]['name'] }}">
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label" for="l0">Soyad</label>
                                <input class="form-control" name="surname" type="text" value="{{ $data[0]['surname'] }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="col-form-label" for="l0">Doğum Tarihi</label>
                                <input class="form-control" name="birth_date" type="date" value="{{ $data[0]['birth_date'] }}">
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label" for="l0">TC. No</label>
                                <input class="form-control" name="tc" type="text" value="{{ $data[0]['tc'] }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-form-label" for="l0">Adres</label>
                                <input class="form-control" name="address" type="text" value="{{ $data[0]['address'] }}">
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label" for="l0">Telefon</label>
                                <input class="form-control" name="phone" type="text" value="{{ $data[0]['phone'] }}">
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label" for="l0">E-mail</label>
                                <input class="form-control" name="email" type="text" value="{{ $data[0]['email'] }}">
                            </div>
                        </div>

                        <div class="form-actions">
                            <div class="form-group row">
                                <div class="col-md-12 ml-md-auto btn-list">
                                    <button class="btn btn-primary btn-rounded" type="submit">Kaydet</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.widget-body -->
            </div>
            <!-- /.widget-bg -->
        </div>
    </div>
</div>
