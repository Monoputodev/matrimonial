@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('Email-Phone')</th>
                                    <th>@lang('Country')</th>
                                    <th>@lang('Joined At')</th>
                                    <th>@lang('Balance')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a
                                                    href="{{ route('admin.users.detail', $user->id) }}"><span>@</span>{{ $user->username }}</a>
                                            </span>
                                        </td>

                                        <td>
                                            {{ $user->email }}<br>{{ $user->mobile }}
                                        </td>
                                        <td>
                                            <span class="fw-bold"
                                                title="{{ @$user->address->country }}">{{ $user->country_code }}</span>
                                        </td>

                                        <td>
                                            {{ showDateTime($user->created_at) }} <br>
                                            {{ diffForHumans($user->created_at) }}
                                        </td>

                                        <td>
                                            <span class="fw-bold">

                                                {{ $general->cur_sym }}{{ showAmount($user->balance) }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="button--group">
                                                <a class="btn btn-sm btn-outline--primary"
                                                    href="{{ route('admin.users.detail', $user->id) }}">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </a>
                                                <button class="btn btn-sm btn-outline--danger"
                                                data-bs-target="#userDeleteModal" data-bs-toggle="modal" type="button">
                                                    <i class="las la-trash"></i> @lang('delete')
                                                </button>
                                                @if (request()->routeIs('admin.users.kyc.pending'))
                                                    <a class="btn btn-sm btn-outline--dark"
                                                        href="{{ route('admin.users.kyc.details', $user->id) }}"
                                                        target="_blank">
                                                        <i class="las la-user-check"></i>@lang('KYC Data')
                                                    </a>
                                                @endif
                                            </div>
                                        </td>

                                    </tr>


                                    <div class="modal fade" id="userDeleteModal" role="dialog" tabindex="-1">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">

                                                            <span>@lang('Remove User')</span>

                                                    </h5>
                                                    <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                                                        <i class="las la-times"></i>
                                                    </button>
                                                </div>
                                                <form action="{{ route('admin.users.delete', $user->id) }}" method="GET">
                                                    @csrf
                                                    <div class="modal-footer">
                                                        @if ($user->status == Status::USER_ACTIVE)
                                                            <button class="btn btn--primary h-45 w-100" type="submit">@lang('Submit')</button>
                                                        @else
                                                            <button class="btn btn--dark" data-bs-dismiss="modal" type="button">@lang('No')</button>
                                                            <button class="btn btn--primary" type="submit">@lang('Yes')</button>
                                                        @endif
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($users->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($users) }}
                    </div>
                @endif
            </div>
        </div>

    </div>





@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Username / Email" />
@endpush
