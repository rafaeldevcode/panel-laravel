@extends('main')

@section('metasConfig')
    <x-meta-config title='Configurações' description='' />
@endsection

@section('content')
    <section class='d-flex flex-nowrap justify-content-between w-100'>
        <x-sidebar />

        <section class='w-100'>
            <x-header />

            {{ App\Actions\SettingsActions::handle() }}

            @include("admin/settings/body/read")
        </section>
    </section>

    <x-footer />
@endsection

@section('scripts')
    <script type="text/javascript">
        getFields();
    </script>
@endsection
