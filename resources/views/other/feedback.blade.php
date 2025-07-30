@extends('layouts.default')
@section('title', 'Feedback')

@section('content')
<div class="container-fluid">
    <!-- begin row -->
    <div class="row d-flex align-items-center justify-content-center" style="min-height: 70vh;">
        <!-- begin col-8 -->
        <div class="col-xl-8 col-lg-10">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Send Us Your Feedback</h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
                <div class="panel-body">
                    @if(session('success'))
                        <div class="alert alert-success text-white bg-primary p-2 rounded">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger text-white bg-danger p-2 rounded">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('other.feedback.submit') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="subject">Subject</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-tag"></i></span>
                                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" class="form-control" placeholder="Enter the subject of your feedback" required>
                            </div>
                            @error('subject') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="message">Message</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-comment-alt"></i></span>
                                <textarea id="message" name="message" class="form-control" rows="5" placeholder="Enter your detailed feedback here..." required>{{ old('message') }}</textarea>
                            </div>
                            @error('message') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Send Feedback</button>
                    </form>
                </div>
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-8 -->
    </div>
    <!-- end row -->
</div>
@endsection