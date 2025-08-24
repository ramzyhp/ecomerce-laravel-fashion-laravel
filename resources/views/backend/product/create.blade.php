@extends('backend.layouts.master')

@section('main-content')
    <div class="card">
        <h5 class="card-header">Tambah Produk</h5>
        <div class="card-body">
            <form method="post" action="{{ route('product.store') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="inputTitle" class="col-form-label">Judul <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="text" name="title" placeholder="Masukkan Judul"
                        value="{{ old('title') }}" class="form-control">
                    @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="summary" class="col-form-label">Ringkasan <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="summary" name="summary">{{ old('summary') }}</textarea>
                    @error('summary')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="col-form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="is_featured">Tampilkan? </label><br>
                    <input type="checkbox" name='is_featured' id='is_featured' value='1' checked> Iya
                </div>
                {{-- {{$categories}} --}}

                <div class="form-group">
                    <label for="cat_id">Kategori <span class="text-danger">*</span></label>
                    <select name="cat_id" id="cat_id" class="form-control">
                        <option value="">--Pilih kategori apapun--</option>
                        @foreach ($categories as $key => $cat_data)
                            <option value='{{ $cat_data->id }}'>{{ $cat_data->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group d-none" id="child_cat_div">
                    <label for="child_cat_id">Sub Kategori</label>
                    <select name="child_cat_id" id="child_cat_id" class="form-control">
                        <option value="">--Pilih sub kategori apapun--</option>
                        {{-- @foreach ($parent_cats as $key => $parent_cat)
                  <option value='{{$parent_cat->id}}'>{{$parent_cat->title}}</option>
              @endforeach --}}
                    </select>
                </div>

                <div class="form-group">
                    <label for="price" class="col-form-label">Harga(Rp) <span class="text-danger">*</span></label>
                    <input id="price" type="number" name="price" placeholder="Masukkan Harga"
                        value="{{ old('price') }}" class="form-control">
                    @error('price')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="discount" class="col-form-label">Diskon(%)</label>
                    <input id="discount" type="number" name="discount" min="0" max="100"
                        placeholder="Masukkan Diskon" value="{{ old('discount') }}" class="form-control">
                    @error('discount')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="size">Ukuran</label>
                    <select name="size[]" class="form-control selectpicker" multiple data-live-search="true">
                        <option value="">--Pilih Ukuran--</option>
                        <option value="S">Small (S)</option>
                        <option value="M">Medium (M)</option>
                        <option value="L">Large (L)</option>
                        <option value="XL">Extra Large (XL)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="brand_id">Brand</label>
                    {{-- {{$brands}} --}}

                    <select name="brand_id" class="form-control">
                        <option value="">--Pilih Brand--</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="condition">Kondisi</label>
                    <select name="condition" class="form-control">
                        <option value="">--Pilih Condition--</option>
                        <option value="default">Default</option>
                        <option value="new">Terbaru</option>
                        <option value="hot">Hot</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="stock">Jumlah <span class="text-danger">*</span></label>
                    <input id="quantity" type="number" name="stock" min="0" placeholder="Masukkan Jumlah"
                        value="{{ old('stock') }}" class="form-control">
                    @error('stock')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="inputPhoto" class="col-form-label">Foto Produk <span class="text-danger">*</span></label>
                    <input type="file" name="photos[]" id="inputPhoto" class="form-control" accept="image/*"
                        multiple onchange="previewMultipleImages(this)">
                    <small class="form-text text-muted">
                        - Pilih beberapa foto sekaligus (maksimal 10 foto)<br>
                        - Format yang diizinkan: JPG, JPEG, PNG, GIF<br>
                        - Ukuran maksimal per foto: 2MB
                    </small>
                    <div id="imagePreview" style="margin-top:15px; display: flex; flex-wrap: wrap; gap: 10px;">
                        <!-- Preview foto akan muncul di sini -->
                    </div>
                    @error('photos')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    @error('photos.*')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control">
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <button class="btn btn-success" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/summernote/summernote.min.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <style>
        #imagePreview img {
            max-width: 100%;
            max-height: 200px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('backend/summernote/summernote.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize summernote
            $('#summary').summernote({
                placeholder: "Tulis deskripsi singkat.....",
                tabsize: 2,
                height: 100
            });

            $('#description').summernote({
                placeholder: "Tulis detail deskripsi.....",
                tabsize: 2,
                height: 150
            });
        });

        // Function to preview multiple images
        function previewMultipleImages(input) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';

            if (input.files && input.files.length > 0) {
                // Check if more than 10 files
                if (input.files.length > 10) {
                    alert('Maksimal 10 foto yang dapat diupload!');
                    input.value = '';
                    return;
                }

                // Loop through all selected files
                for (let i = 0; i < input.files.length; i++) {
                    const file = input.files[i];

                    // Validate file size (2MB)
                    if (file.size > 2048000) {
                        alert(`Foto "${file.name}" terlalu besar! Maksimal 2MB per foto.`);
                        input.value = '';
                        preview.innerHTML = '';
                        return;
                    }

                    // Validate file type
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    if (!allowedTypes.includes(file.type)) {
                        alert(`Format foto "${file.name}" tidak didukung! Gunakan JPG, JPEG, PNG, atau GIF.`);
                        input.value = '';
                        preview.innerHTML = '';
                        return;
                    }

                    // Create preview for each file
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Create container for each image
                        const imageContainer = document.createElement('div');
                        imageContainer.style.position = 'relative';
                        imageContainer.style.display = 'inline-block';
                        imageContainer.style.margin = '5px';

                        // Create image element
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.width = '150px';
                        img.style.height = '150px';
                        img.style.objectFit = 'cover';
                        img.style.border = '2px solid #ddd';
                        img.style.borderRadius = '8px';
                        img.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';

                        // Create label for image number
                        const label = document.createElement('div');
                        label.innerHTML = `Foto ${i + 1}`;
                        label.style.textAlign = 'center';
                        label.style.fontSize = '12px';
                        label.style.fontWeight = 'bold';
                        label.style.marginTop = '5px';
                        label.style.color = '#555';

                        // Add elements to container
                        imageContainer.appendChild(img);
                        imageContainer.appendChild(label);
                        preview.appendChild(imageContainer);
                    };
                    reader.readAsDataURL(file);
                }

                // Show total selected files
                const totalInfo = document.createElement('div');
                totalInfo.innerHTML = `<strong>Total: ${input.files.length} foto dipilih</strong>`;
                totalInfo.style.width = '100%';
                totalInfo.style.textAlign = 'center';
                totalInfo.style.marginTop = '10px';
                totalInfo.style.padding = '10px';
                totalInfo.style.backgroundColor = '#e9f7ef';
                totalInfo.style.border = '1px solid #27ae60';
                totalInfo.style.borderRadius = '4px';
                totalInfo.style.color = '#27ae60';
                preview.appendChild(totalInfo);
            }
        }
    </script>

    <script>
        $('#cat_id').change(function() {
            var cat_id = $(this).val();
            // alert(cat_id);
            if (cat_id != null) {
                // Ajax call
                $.ajax({
                    url: "/admin/category/" + cat_id + "/child",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: cat_id
                    },
                    type: "POST",
                    success: function(response) {
                        if (typeof(response) != 'object') {
                            response = $.parseJSON(response)
                        }
                        // console.log(response);
                        var html_option = "<option value=''>----Select sub category----</option>"
                        if (response.status) {
                            var data = response.data;
                            // alert(data);
                            if (response.data) {
                                $('#child_cat_div').removeClass('d-none');
                                $.each(data, function(id, title) {
                                    html_option += "<option value='" + id + "'>" + title +
                                        "</option>"
                                });
                            } else {}
                        } else {
                            $('#child_cat_div').addClass('d-none');
                        }
                        $('#child_cat_id').html(html_option);
                    }
                });
            } else {}
        })
    </script>
@endpush
