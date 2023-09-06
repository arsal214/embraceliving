<script
    src="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/2.0.3/browser/overlayscrollbars.browser.es6.min.js"
    integrity="sha512-IVhd/31Olt0desTEgGT/Izo7x21G/hysVPky3ZL07ifZq1KVPWTOSG6CLltZf6Mrb/q0EW3tiDGi97/mfnWWyg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"
        integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js" integrity="sha512-KBeR1NhClUySj9xBB0+KRqYLPkM6VvXiiWaSz/8LCQNdRpUm38SWUrj0ccNDNSkwCD9qPA4KobLliG26yPppJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script src="{{ asset('theme/select2/select2.js') }}"></script>
<script src="{{ asset('theme/dropify/dist/js/dropify.min.js') }}"></script>



<script src="{{ asset('js/custom.js') }}"></script>

<script>
    $(document).ready(function() {
        // Basic
        $('.dropify').dropify();
    });
    $(function() {
        const select2 = $('.select2');
        if (select2.length) {
            select2.each(function() {
                var $this = $(this);
                select2Focus($this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Select value',
                    dropdownParent: $this.parent()
                });
            });
        }
    });
</script>

@yield('page-script')

@stack('js/app.js')
