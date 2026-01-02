{{-- @extends('dashboard.layouts.master')
@section('title', __('General Package'))
@push('after-styles')
    <link href="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.min.css") }}" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
          <style>

     .box-header.dker {
            background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%) !important;
            color: white;
             border-radius: 5px 5px 0 0;
             font-size: 18px;
        }
        .box-header.dker h3 {
            font-size: 22px;
            font-weight: 700;
        }
        .box-tool {
            color: white;
        }
     .btn-primary
          {
            background: #A0C242 !important;
            border-color: #A0C242 !important;
        }
  
        .btn-primary:hover {
            background: #8AAE38 !important;
            border-color: #8AAE38 !important;
        }
    
    
    </style>
@endpush
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe02e;</i> {{ __('New General Package') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{{__('new-general-package')}}</a> 
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline">
                        <a class="nav-link" href="#">
                            <i class="material-icons md-18">×</i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="box-body p-a-2">
                {{Form::open(['route'=>['generalticketpackagesStore'],'method'=>'POST', 'files' => true])}}
                    <div class="form-group row">
                        <label for="title"
                                class="col-sm-2 form-control-label">{!!  __('Title') !!}
                        </label>
                        <div class="col-sm-10">
                            {!! Form::text('title','', array('placeholder' => '','class' => 'form-control','id'=>'title','required'=>'')) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="details"
                            class="col-sm-2 form-control-label">{!!  __('Description') !!}
                        </label>
                        <div class="col-sm-10">
                                @foreach (Helper::languagesList() as $ActiveLanguage)
                                @if ($ActiveLanguage->box_status)
                                    <div class="m-b-1">
                                        {!!  __('backend.customFieldsType99') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                        <div class="box p-a-xs">
                                            {!! Form::textarea(
                                                'description',
                                                optional(Session::get('WebmasterSectionField'))->{'details_' . @$ActiveLanguage->code},
                                                [
                                                    'ui-jp' => 'summernote',
                                                    'placeholder' => '',
                                                    'class' => 'form-control summernote_' . @$ActiveLanguage->code,
                                                    'dir' => @$ActiveLanguage->direction,
                                                    'ui-options' => '{height: 150}'
                                                ]
                                            ) !!}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="photo"
                            class="col-sm-2 form-control-label">{!!  __('Image') !!}</label>
                        <div class="col-sm-10">
                            {!! Form::file('photo[]', array('class' => 'form-control','id'=>'photo','accept'=>'image/*','multiple' => 'multiple')) !!}
                        </div>
                    </div>

                    <div class="form-group row m-t-md" style="margin-top: 0 !important;">
                        <div class="offset-sm-2 col-sm-10">
                            <small>
                                <i class="material-icons">&#xe8fd;</i>
                                {!!  __('backend.imagesTypes') !!}
                            </small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="link_status" class="col-sm-2 form-control-label">Status</label>
                        <div class="col-sm-10">
                            <div class="radio">
                                <label class="ui-check ui-check-md">
                                    <input id="status1" class="has-value" checked="checked" name="status" type="radio" value="1">
                                    <i class="dark-white"></i>
                                    Active
                                </label>
                                &nbsp; &nbsp;
                                <label class="ui-check ui-check-md">
                                    <input id="status2" class="has-value" name="status" type="radio" value="0">
                                    <i class="dark-white"></i>
                                    Not Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button"
                        class="btn dark-white p-x-md"
                        data-dismiss="modal">{{ __('backend.cancel') }}</button>
                <button type="submit"
                        class="btn btn-primary p-x-md">{!! __('backend.add') !!}</button>
            </div>
                {{Form::close()}}
            
        </div>
    </div>
@endsection
@push('after-scripts')
    <script src="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.js") }}"></script>
    <script>
        $(function () {
            $('.icp-auto').iconpicker({placement: '{{ (@Helper::currentLanguage()->direction=="rtl")?"topLeft":"topRight" }}'});
        });
    </script>
@endpush --}}




@extends('dashboard.layouts.master')
@section('title', __('General Package'))
@push('after-styles')
    <link href="{{ asset('assets/dashboard/js/iconpicker/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
    <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
            <![endif]-->
    <style>
        .box-header.dker {
            background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%) !important;
            color: white;
            border-radius: 5px 5px 0 0;
            font-size: 18px;
        }

        .box-header.dker h3 {
            font-size: 22px;
            font-weight: 700;
        }

        .box-tool {
            color: white;
        }

        .btn-primary {
            background: #A0C242 !important;
            border-color: #A0C242 !important;
        }

        .btn-primary:hover {
            background: #8AAE38 !important;
            border-color: #8AAE38 !important;
        }

        /* Multi-step form styles */
        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .step-item {
            text-align: center;
            flex: 1;
            position: relative;
        }

        .step-item:not(:last-child):after {
            content: '';
            position: absolute;
            top: 15px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: #dee2e6;
            z-index: 1;
        }

        .step-item.active:not(:last-child):after {
            background: #A0C242;
        }

        .step-bubble {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 5px;
            position: relative;
            z-index: 2;
        }

        .step-item.active .step-bubble {
            background: #A0C242;
            color: white;
        }

        .step-item.completed .step-bubble {
            background: #8AAE38;
            color: white;
        }

        .form-navigation {
            display: flex;
            justify-content: end;
            margin-top: 20px;
            gap: 20px;
        }

        /* Modal styles */
        .custom-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            overflow-y: auto;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .custom-modal.show {
            display: block;
            opacity: 1;
        }

        .custom-modal-content {
            background: white;
            margin: 50px auto;
            border-radius: 10px;
            max-width: 800px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transform: scale(0.7);
            opacity: 0;
            transition: all 0.4s ease;
        }

        .custom-modal.show .custom-modal-content {
            transform: scale(1);
            opacity: 1;
        }

        .custom-modal-header {
            background: linear-gradient(135deg, #A0C242 0%, #8AAE38 100%) !important;
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .custom-modal-body {
            padding: 20px;
        }

        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            transition: transform 0.2s ease;
            padding: 5px;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-btn:hover {
            transform: scale(1.1);
            background: rgba(255, 255, 255, 0.2);
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }

        .is-invalid~.invalid-feedback {
            display: block;
        }

        /* Background blur effect when modal is open */
        body.modal-open {
            overflow: hidden;
        }

        body.modal-open .padding,
        body.modal-open .navbar,
        body.modal-open .sidebar,
        body.modal-open .app-aside {
            filter: blur(5px);
            transition: filter 0.3s ease;
        }

        /* Remove blur when modal is closed */
        body:not(.modal-open) .padding,
        body:not(.modal-open) .box,
        body:not(.modal-open) .navbar,
        body:not(.modal-open) .sidebar {
            filter: blur(0);
            transition: filter 0.3s ease;
        }
    </style>
@endpush

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker" style="display: none;">
                <h3><i class="material-icons">&#xe02e;</i> {{ __('New General Package') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{{ __('new-general-package') }}</a>
                </small>
            </div>
            <div class="box-body" style="display: none;">
                <!-- Button to trigger modal -->
                <button type="button" class="btn btn-primary" id="openPackageModal">
                    <i class="material-icons">&#xe02e;</i> {{ __('Create New Package') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Custom Modal -->
    <div class="custom-modal" id="packageModal">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5 class="modal-title">
                    <i class="material-icons">&#xe02e;</i> {{ __('Create New General Package') }}
                </h5>
                <button type="button" class="close-btn" id="closePackageModal"
                    title="Back to previous page">&times;</button>
            </div>
            <div class="custom-modal-body">
                <!-- Multi-step form -->
                <div class="step-indicator">
                    <div class="step-item active" data-step="1">
                        <div class="step-bubble">1</div>
                        <small>Basic Info</small>
                    </div>
                    <div class="step-item" data-step="2">
                        <div class="step-bubble">2</div>
                        <small>Description</small>
                    </div>
                    <div class="step-item" data-step="3">
                        <div class="step-bubble">3</div>
                        <small>Media & Status</small>
                    </div>
                </div>

                {{ Form::open(['route' => ['generalticketpackagesStore'], 'method' => 'POST', 'files' => true, 'id' => 'multiStepForm']) }}

                <!-- Step 1: Basic Information -->
                <div class="step active" id="step1">
                    <div class="form-group row">
                        <label for="title" class="col-sm-3 form-control-label">
                            {!! __('Title') !!} <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" name="title" placeholder="Enter package title" class="form-control"
                                id="title" required maxlength="35">
                            <div class="invalid-feedback">Please enter package title</div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Description -->
                <div class="step" id="step2">
                    <div class="form-group row">
                        <label for="details" class="col-sm-3 form-control-label">{!! __('Description') !!}</label>
                        <div class="col-sm-9">
                            @foreach (Helper::languagesList() as $ActiveLanguage)
                                @if ($ActiveLanguage->box_status)
                                    <div class="m-b-1">
                                        {!! __('backend.customFieldsType99') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                        <div class="box p-a-xs">
                                            {!! Form::textarea(
                                                'description',
                                                optional(Session::get('WebmasterSectionField'))->{'details_' . @$ActiveLanguage->code},
                                                [
                                                    'ui-jp' => 'summernote',
                                                    'placeholder' => '',
                                                    'class' => 'form-control summernote_' . @$ActiveLanguage->code,
                                                    'dir' => @$ActiveLanguage->direction,
                                                    'ui-options' => '{height: 150}',
                                                ],
                                            ) !!}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Step 3: Media & Status -->
                <div class="step" id="step3">
                    <div class="form-group row mb-3">
                        <label for="photo" class="col-sm-3 form-control-label">{!! __('Image') !!}</label>
                        <div class="col-sm-9">
                            <input type="file" name="photo[]" class="form-control" id="photo" accept="image/*"
                                multiple>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="offset-sm-3 col-sm-9">
                            <small class="text-muted">
                                <i class="material-icons">&#xe8fd;</i>
                                {!! __('backend.imagesTypes') !!}
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 form-control-label">Status</label>
                        <div class="col-sm-9">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status1" value="1"
                                    checked>
                                <label class="form-check-label" for="status1">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status2" value="0">
                                <label class="form-check-label" for="status2">Not Active</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="form-navigation mt-4">
                    <button type="button" class="btn btn-secondary" id="prevBtn" onclick="nextPrev(-1)"
                        style="display: none;">
                        <i class="material-icons">&#xe5c4;</i> {{ __('Previous') }}
                    </button>
                    <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextPrev(1)">
                        {{ __('Next') }} <i class="material-icons">&#xe5c8;</i>
                    </button>
                    <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                        <i class="material-icons">&#xe161;</i> {!! __('Create Package') !!}
                    </button>
                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/dashboard/js/iconpicker/fontawesome-iconpicker.js') }}"></script>

    <script>
        // Custom Modal Functions
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('packageModal');
            const openBtn = document.getElementById('openPackageModal');
            const closeBtn = document.getElementById('closePackageModal');
            const body = document.body;

            // Function to open modal with animation
            function openModal() {
                body.classList.add('modal-open');
                modal.classList.add('show');

                // Force reflow for smooth animation
                modal.offsetHeight;
            }

            // Function to close modal with animation
            function closeModal() {
                body.classList.remove('modal-open');
                modal.classList.remove('show');
                resetForm();
            }

            // Function to go back to previous page
            function goBackToPreviousPage() {
                // Check if there's history, otherwise go to admin home
                if (window.history.length > 1) {
                    window.history.back();
                } else {
                    window.location.href = "{{ route('adminHome') }}";
                }
            }

            // Open modal automatically on page load
            setTimeout(() => {
                openModal();
            }, 300);

            // Open modal manually (if needed)
            openBtn.addEventListener('click', function() {
                openModal();
            });

            closeBtn.addEventListener('click', function() {
                goBackToPreviousPage();
            });

            // Initialize icon picker
            if (typeof jQuery !== 'undefined') {
                $('.icp-auto').iconpicker({
                    placement: '{{ @Helper::currentLanguage()->direction == 'rtl' ? 'topLeft' : 'topRight' }}'
                });
            }

            showStep(1);
        });

        // Multi-step form functionality (Pure JavaScript)
        let currentStep = 1;

        function showStep(n) {
            const steps = document.getElementsByClassName("step");
            const stepIndicators = document.getElementsByClassName("step-item");

            if (n > steps.length) {
                return false;
            }
            if (n < 1) {
                n = 1;
            }

            // Hide all steps
            for (let i = 0; i < steps.length; i++) {
                steps[i].classList.remove("active");
            }

            // Show current step
            steps[n - 1].classList.add("active");

            // Update step indicators
            for (let i = 0; i < stepIndicators.length; i++) {
                stepIndicators[i].classList.remove("active", "completed");
                if (i < n - 1) {
                    stepIndicators[i].classList.add("completed");
                } else if (i === n - 1) {
                    stepIndicators[i].classList.add("active");
                }
            }

            // Update buttons
            const prevBtn = document.getElementById("prevBtn");
            const nextBtn = document.getElementById("nextBtn");
            const submitBtn = document.getElementById("submitBtn");

            if (n === 1) {
                prevBtn.style.display = "none";
            } else {
                prevBtn.style.display = "inline-block";
            }

            if (n === steps.length) {
                nextBtn.style.display = "none";
                submitBtn.style.display = "inline-block";
            } else {
                nextBtn.style.display = "inline-block";
                submitBtn.style.display = "none";
            }

            currentStep = n;
        }

        function nextPrev(n) {
            // Validate current step before proceeding
            if (n === 1 && !validateStep()) {
                return false;
            }

            showStep(currentStep + n);
        }

        function validateStep() {
            let valid = true;
            const currentStepElement = document.getElementById("step" + currentStep);
            const inputs = currentStepElement.querySelectorAll("input[required], select[required]");

            // Reset validation
            inputs.forEach(input => {
                input.classList.remove('is-invalid');
                const feedback = input.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.style.display = 'none';
                }
            });

            // Check required fields
            for (let i = 0; i < inputs.length; i++) {
                if (!inputs[i].value.trim()) {
                    inputs[i].classList.add("is-invalid");
                    valid = false;

                    // Show error message
                    const feedback = inputs[i].nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.style.display = 'block';
                    }

                    // Scroll to first invalid field
                    if (valid === false) {
                        inputs[i].scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        break;
                    }
                }
            }

            return valid;
        }

        function resetForm() {
            // Reset form
            document.getElementById('multiStepForm').reset();
            // Reset to first step
            showStep(1);
            // Remove validation classes
            const inputs = document.querySelectorAll('.is-invalid');
            inputs.forEach(input => {
                input.classList.remove('is-invalid');
            });
        }

        // Form submission
        document.getElementById('multiStepForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate all steps before submission
            let allValid = true;
            for (let i = 1; i <= 3; i++) {
                const stepElement = document.getElementById("step" + i);
                const inputs = stepElement.querySelectorAll("input[required], select[required]");

                for (let j = 0; j < inputs.length; j++) {
                    if (!inputs[j].value.trim()) {
                        inputs[j].classList.add("is-invalid");
                        allValid = false;
                    }
                }
            }

            if (allValid) {
                // Submit the form
                this.submit();
            } else {
                alert('Please fill all required fields');
                showStep(1);
            }
        });
    </script>
@endpush
