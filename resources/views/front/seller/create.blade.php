<div class="widget-list">
    <div class="row">
        <div class="col-md-12 widget-holder">
            <div class="widget-bg">
                <div class="widget-body clearfix">
                    <form action="{{ route('seller.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="col-form-label" for="l0">Ad</label>
                                <input class="form-control" name="name" type="text">
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label" for="l0">Soyad</label>
                                <input class="form-control" name="surname" type="text">
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
