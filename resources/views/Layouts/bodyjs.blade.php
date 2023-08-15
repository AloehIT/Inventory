<div class="rightbar-overlay"></div>
<!-- /End-bar -->


<!-- bundle -->
<script src="{{ URL::to('assets/js/vendor.min.js') }}"></script>
<script src="{{ URL::to('assets/js/app.min.js') }}"></script>
<script src="{{ URL::to('assets/js/main.js') }}"></script>

<!-- Apex js -->
<script src="{{ URL::to('assets/js/vendor/apexcharts.min.js') }}"></script>

<!-- Todo js -->
<script src="{{ URL::to('assets/js/ui/component.todo.js') }}"></script>

<!-- demo app -->
<script src="{{ URL::to('assets/js/pages/demo.dashboard-crm.js') }}"></script>
<script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
<script src="{{ URL::to('assets/js/pages/demo.customers.js') }}"></script>
<!-- end demo js-->

<!-- data table js-->
<script src="{{ URL::to('assets/js/vendor/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::to('assets/js/vendor/dataTables.bootstrap5.js') }}"></script>
<!-- end data table js-->

{{-- upload img --}}
<script>
    imgInp.onchange = evt => {
                const [file] = imgInp.files
                if (file) {
                    blah.src = URL.createObjectURL(file)
                }
            }
</script>

<script>
    imgKtp.onchange = evt => {
                const [file] = imgKtp.files
                if (file) {
                    ktp.src = URL.createObjectURL(file)
                }
            }
</script>
<script src="{{URL::to('assets/js/vendor/simplemde.min.js')}}"></script>
<script src="{{URL::to('assets/js/pages/demo.simplemde.js')}}"></script>


<div id="stokKosongModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body">
                <div class="text-start mt-2 mb-2 mx-3">
                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            <h5 class="text-uppercase mb-0"><i class="bi bi-database-slash text-danger"></i> Stok Kosong</h5>
                        </div>

                        <a type="button" data-bs-dismiss="modal" class="text-danger" style="font-size: 25px;"><i class="uil-multiply"></i></a>
                   </div>
                </div>

                <div class="ps-3 pe-3">
                    <div class="">
                        <div class="mb-3">
                            Opss, stok masih kosong!
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('sweetalert::alert')
