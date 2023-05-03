@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card bl--5-primary">
                <div class="card-body">
                    <p class="text--primary">@lang('While you are adding a new keyword, it will only add to this current language only. Please be careful on entering a keyword, please make sure there is no extra space. It needs to be exact and case-sensitive.')</p>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two custom-data-table">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Code')</th>
                                    <th>@lang('Default')</th>
                                    <th>@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($languages as $item)
                                    <tr>
                                        <td>{{ __($item->name) }}</td>
                                        <td><strong>{{ __($item->code) }}</strong></td>
                                        <td>
                                            @if ($item->is_default == Status::YES)
                                                <span class="badge badge--success">@lang('Default')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Selectable')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <a class="btn btn-sm btn-outline--success" href="{{ route('admin.language.key', $item->id) }}">
                                                    <i class="la la-code"></i> @lang('Translate')
                                                </a>
                                                <a class="btn btn-sm btn-outline--primary ms-1 editBtn" data-lang="{{ json_encode($item->only('name', 'text_align', 'is_default')) }}" data-url="{{ route('admin.language.manage.update', $item->id) }}" href="javascript:void(0)">
                                                    <i class="la la-pen"></i> @lang('Edit')
                                                </a>
                                                @if ($item->id != 1)
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.language.manage.delete', $item->id) }}" data-question="@lang('Are you sure to remove this language from this system?')">
                                                        <i class="la la-trash"></i> @lang('Remove')
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
            </div><!-- card end -->
        </div>
    </div>

    {{-- NEW MODAL --}}
    <div aria-hidden="true" aria-labelledby="createModalLabel" class="modal fade" id="createModal" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="createModalLabel"> @lang('Add New Language')</h4>
                    <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><i class="las la-times"></i></button>
                </div>
                <form action="{{ route('admin.language.manage.store') }}" class="form-horizontal" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row form-group">
                            <label>@lang('Language Name')</label>
                            <div class="col-sm-12">
                                <input class="form-control" name="name" required type="text" value="{{ old('name') }}">
                            </div>
                        </div>

                        <div class="row form-group">
                            <label>@lang('Language Code')</label>
                            <div class="col-sm-12">
                                <input class="form-control" name="code" required type="text" value="{{ old('code') }}">
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="inputName">@lang('Default Language')</label>
                                <input data-bs-toggle="toggle" data-height="40px" data-off="@lang('UNSET')" data-offstyle="-danger" data-on="@lang('SET')" data-onstyle="-success" data-width="100%" name="is_default" type="checkbox">
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--primary w-100 h-45" id="btn-save" type="submit" value="add">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div aria-hidden="true" aria-labelledby="editModalLabel" class="modal fade" id="editModal" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editModalLabel">@lang('Edit Language')</h4>
                    <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><i class="las la-times"></i></button>
                </div>
                <form method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Language Name')</label>
                            <div class="col-sm-12">
                                <input class="form-control" name="name" required type="text" value="{{ old('name') }}">
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label for="inputName">@lang('Default Language')</label>
                            <input data-bs-toggle="toggle" data-height="40px" data-off="@lang('UNSET')" data-offstyle="-danger" data-on="@lang('SET')" data-onstyle="-success" data-width="100%" name="is_default" type="checkbox">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--primary w-100 h-45" id="btn-save" type="submit" value="add">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a class="btn btn-sm btn-outline--primary" data-bs-target="#createModal" data-bs-toggle="modal"><i class="las la-plus"></i>@lang('Add New')</a>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.editBtn').on('click', function() {
                var modal = $('#editModal');
                var url = $(this).data('url');
                var lang = $(this).data('lang');

                modal.find('form').attr('action', url);
                modal.find('input[name=name]').val(lang.name);
                modal.find('select[name=text_align]').val(lang.text_align);
                if (lang.is_default == 1) {
                    modal.find('input[name=is_default]').bootstrapToggle('on');
                } else {
                    modal.find('input[name=is_default]').bootstrapToggle('off');
                }
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
