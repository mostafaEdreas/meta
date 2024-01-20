
function makeRestoreModal(product){
    let body = `
    <form action="{{url('productes/restore')}}/"${product.id} method="post">
    هل انت متاكد من الغاء حذف ${product.name}
    {!! @csrf !!}

    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">الغاء</button>
    <button type="submit" class="btn btn-primary" id="btnActionDailoge">الغاء حذف</button>
        </form>
    `
    $('#titleDailoge').html('الغاء حذف');
    $('#titleDailoge').html(body)
}

function makeDeleteModal(product){
    let body = `
    <form action="{{url('productes/delete')}}/"${product.id} method="post">
    هل انت متاكد من حذف ${product.name}
   {!! @csrf !!}
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">الغاء</button>
    <button type="submit" class="btn btn-primary" id="btnActionDailoge"> حذف</button>
        </form>
    `
    $('#titleDailoge').html(' حذف');
    $('#titleDailoge').html(body)
}

function makeSaveModal(){
    let body = `
    <form action="{{url('productes/store')}}" method="post"  enctype="multipart/form-data">
        <input class="form-control form-control-lg" name="name" type="text" placeholder="الاسم" aria-label=".form-control-lg example">
        <div>
            <label for="" class="form-label">Large file input example</label>
            <input class="form-control form-control-lg" id=""  imagetype="file">
        </div>
        {!! @csrf !!}
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">الغاء</button>
    <button type="submit" class="btn btn-primary" id="btnActionDailoge">حفــظ</button>
        </form>
    `
    $('#titleDailoge').html('حفــظ');
    $('#titleDailoge').html(body)
}