<!-- jQuery library -->
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    <script src="{{ asset('assets/js/jq.js') }}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.js"></script> --}}
    <script src="{{ asset('assets/js/select2.js') }}"></script>

    <!-- Latest compiled JavaScript -->
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#logout').click(function(e) {
                e.preventDefault(); // Prevent the default behavior of the anchor tag

                // Trigger form submission when the anchor is clicked
                $('#logoutForm').submit();
            });
        });
    </script>
    <script>
        $('#userImage').change(function() {
            $('#formImg').submit();
        });
    </script>
    <script>
        function makeRestoreModal(product) {
            let body = `
                <form action="{{ url('products/restore') }}/${product.id}" method="post">
                    <h3>هل انت متاكد من الغاء حذف <span style="color:green">${product.name}</span></h3>
                    @csrf
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-primary" id="btnActionDailoge">الغاء حذف</button>
                    </div>
                </form>`
            $('#titleDailoge').html('الغاء حذف');
            $('#bodyDailoge').html(body)
        }

        function makeDeleteModal(product) {
            let body = `
                    <form action="{{ url('products/distroy') }}/${product.id}" method="post">
                    <h3>  هل انت متاكد من حذف <span style =" color:red;" > ${product.name} </span> </h3>
                        @csrf 
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">الغاء</button>
                            <button type="submit" class="btn btn-primary" id="btnActionDailoge"> حذف</button>
                        </div>
                    </form> `
            $('#titleDailoge').html(' حذف');
            $('#bodyDailoge').html(body)
        }

        function makeSaveModal() {
            let body = `
                <form action="{{ url('products/store') }}" method="post"  enctype="multipart/form-data">
                    <input class="form-control form-control-lg mb-3" name="name" type="text" placeholder="الاسم" aria-label=".form-control-lg example">
                    <input class="form-control form-control-lg mb-3" id="" name="img" type="file" >
                    @csrf 
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-primary" id="btnActionDailoge">حفــظ</button>
                    </div>
                </form>`
            $('#titleDailoge').html('حفــظ');
            $('#bodyDailoge').html(body)
        }
    </script>
    @yield('script')
    <script>
        dataSale = JSON.parse(localStorage.getItem('{{ auth()->user()->id }}' + 'order')) || [];
        dataPurch = JSON.parse(localStorage.getItem('{{ auth()->user()->id }}' + 'purchase')) || [];
        if (dataPurch.length > 0) {
            $('#purchLink').html(`المشتريات<span class="bg-danger pe-1 rounded-3 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart4" viewBox="0 0 16 16">
                    <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5M3.14 5l.5 2H5V5zM6 5v2h2V5zm3 0v2h2V5zm3 0v2h1.36l.5-2zm1.11 3H12v2h.61zM11 8H9v2h2zM8 8H6v2h2zM5 8H3.89l.5 2H5zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0"/>
                </svg>
            </span>`)
        }
        if (dataSale.length > 0) {
            $('#saleLink').html(`المبيعات<span class="bg-danger pe-1 rounded-3 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart4" viewBox="0 0 16 16">
                    <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5M3.14 5l.5 2H5V5zM6 5v2h2V5zm3 0v2h2V5zm3 0v2h1.36l.5-2zm1.11 3H12v2h.61zM11 8H9v2h2zM8 8H6v2h2zM5 8H3.89l.5 2H5zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0"/>
                </svg>
            </span>`)
        }
    </script>
    <script>
        $(document).ready(function() {
            $(".select2").select2();
            $(".selectT").select2({
                width: '300px', // Set the width explicitly
            })
        });
    </script>