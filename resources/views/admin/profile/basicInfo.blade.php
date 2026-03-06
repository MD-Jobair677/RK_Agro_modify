<div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
    <div class="card mb-4">
        <div class="card-body">
            <div class="user-avatar-section">
                <div class=" d-flex align-items-center flex-column">
                    <img class="img-fluid rounded my-4" src="{{ getImage(getFilePath('adminProfile').'/'.@$admin->image, getFileSize('adminProfile')) }}" height="110" width="110" alt="admin" />
                    <div class="user-info text-center">
                        <h4 class="mb-2">{{ __($admin->name) }}</h4>
                        <span class="badge bg-label-secondary">@lang('Admin')</span>
                    </div>
                </div>
            </div>
            <h5 class="pb-2 border-bottom mb-4 mt-4"></h5>
            <div class="info-container">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td class="px-0 w-40"><span class="fw-medium me-2">@lang('Username'):</span></td>
                            <td class="text-end px-0 w-60"><span>{{ $admin->username }}</span></td>
                        </tr>
                        <tr>
                            <td class="px-0 w-40"><span class="fw-medium me-2">@lang('Email'):</span></td>
                            <td class="text-end px-0 w-60"><span>{{ $admin->email }}</span></td>
                        </tr>
                        <tr>
                            <td class="px-0 w-40"><span class="fw-medium me-2">@lang('Status'):</span></td>
                            <td class="text-end px-0 w-60"><span class="badge bg-label-success">@lang('Active')</span></td>
                        </tr>
                        <tr>
                            <td class="px-0 w-40"><span class="fw-medium me-2">@lang('Contact'):</span></td>
                            <td class="text-end px-0 w-60"><span>{{ $admin->contact }}</span></td>
                        </tr>
                        <tr>
                            <td class="px-0 w-40"><span class="fw-medium me-2">@lang('Address'):</span></td>
                            <td class="text-end px-0 w-60"><span>{{ __($admin->address) }}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
