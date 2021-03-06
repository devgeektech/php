@extends('voyager::master')

@section('title', 'Create News')

@section('content')

<section class="content-header">
    <h1>
        Create News
        <small><a href="{{ route('admin.news.index') }}" class="btn btn-block btn-xs btn-warning btn-flat"><i class="fa fa-plus"></i> BACK</a></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Forms</a></li>
        <li class="active">General Elements</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" role="form">
            @csrf

            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="newstitle">Title</label>
                            <input type="text" name="title" class="form-control" id="newstitle">
                        </div>
                        <div class="form-group">
                            <label>News Details</label>
                            <textarea class="textarea" name="details" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Categories</label>
                            <select name="category_id" class="form-control select2" style="width: 100%;">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="newsimage">Featured Image</label>
                            <input type="file" name="image" id="newsimage">
                            <p class="help-block">(Image must be in .png or .jpg format)</p>
                        </div>
                        <hr>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="status"> Published
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="featured"> Featured
                            </label>
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary btn-flat">CREATE</button>
                    </div>
                </div>
            </div>

        </form>

    </div>
</section>

@endsection
