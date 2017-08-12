@extends('admin.dashboard')

@section('CSS')
    <!-- iCheck -->
    <link href="{{ asset("/adminlte/plugins/iCheck/all.css")}}" rel="stylesheet" type="text/css" />

    <!-- Select 2 -->
    {!! Html::style('/adminlte/plugins/select2/select2.min.css') !!}

    <style>
        .select2
        {
            width: 100% !important;
        }

        .wrapper-icheckbox
        {
            margin-right: 5px;
        }

        .table-name
        {
            line-height: 90px;
            margin-left: 20px;
        }

        .label-table-name
        {
            cursor: pointer;
        }

        .block-form .box-title
        {
            border-bottom: 1px solid #f4f4f4;
            padding-bottom: 10px;
            text-align: center;
        }

        .no-relation
        {
            text-align: center;
            margin-top: 60px;
        }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-4">
            {!! BootForm::open() !!}
            {!! BootForm::select('Go to', 'goto-table')->class('select2')->options($aGotoOptions)->select('')->attribute('autocomplete', 'off') !!}
            {!! BootForm::close() !!}
        </div>
    </div>
    {!! BootForm::open()->action( route('admin.entity.store') )->post() !!}
    @foreach($aTables as $sTable => $arelations)
    <div class="row">
        <div class="col-sm-8">
            <br>
            <div id="box-{{ $sTable }}" class="box box-info">
                <div class="box-header with-border">
                    <h2 class="box-title">{{ $sTable }}</h2>
                </div>
                <div class="box-body">

                    <div class="col-sm-4 block-form">
                        <h4 class="box-title">Files</h4>
                        {!! BootForm::checkbox('All', '')
                            ->class('minimal all-check') !!}

                        {!! BootForm::checkbox('Controller', 'table['.$sTable.'][controller]')
                            ->class('minimal') !!}

                        {!! BootForm::checkbox('Model', 'table['.$sTable.'][model]')
                            ->class('minimal') !!}

                        {!! BootForm::checkbox('Repository', 'table['.$sTable.'][repository]')
                            ->class('minimal') !!}

                        {!! BootForm::checkbox('Request', 'table['.$sTable.'][request]')
                            ->class('minimal') !!}

                        {!! BootForm::checkbox('Index view', 'table['.$sTable.'][index]')
                            ->class('minimal') !!}

                        {!! BootForm::checkbox('Form view', 'table['.$sTable.'][form]')
                            ->class('minimal') !!}

                        {!! BootForm::checkbox('Traduction', 'table['.$sTable.'][traduction]')
                            ->class('minimal') !!}

                        {!! BootForm::checkbox('Route web', 'table['.$sTable.'][routeweb]')
                            ->class('minimal') !!}

                        {!! BootForm::checkbox('Route api', 'table['.$sTable.'][routeapi]')
                            ->class('minimal') !!}

                        {!! BootForm::checkbox('Navbar', 'table['.$sTable.'][navbar]')
                            ->class('minimal') !!}
                    </div>

                    <div class="col-sm-8 block-form">
                        <h4 class="box-title">Relations</h4>
                        @if(count($arelations) > 0)
                            <table class="table table-condensed table-bordered">
                                <tr>
                                    <th>Table</th>
                                    <th>Relations</th>
                                </tr>
                                @foreach($arelations as $sRelation => $aRelats)

                                    <tr>
                                        <td>
                                            <big><b>{{ $sRelation }}</b></big>
                                        </td>
                                        <td>
                                            @if(count($aRelats) > 0)
                                                {!!
                                                    BootForm::select('', 'related-'. $sTable.'[]')
                                                        ->class('select2')
                                                        ->options($aRelationOptions[$sTable])
                                                        ->select('0')
                                                        ->attribute('autocomplete', 'off')
                                                !!}
                                            @else
                                                {{ __('entity.standard_relation') }}
                                            @endif
                                        </td>
                                    </tr>

                                @endforeach
                            </table>
                        @else
                        <div class="row no-relation"><b>{{ __('entity.no_relations') }}</b></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    {!! BootForm::submit('Save', 'btn-primary') !!}

    {!! BootForm::close() !!}
@stop

@section('JS')
    <script src="{{ asset ("/adminlte/plugins/iCheck/icheck.min.js") }}"></script>
    <!-- Select 2 -->
    {!! Html::script('/adminlte/plugins/select2/select2.full.min.js') !!}

    <script type="text/javascript">
        $(document).ready(function() {
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue wrapper-icheckbox',
                radioClass: 'iradio_minimal-blue'
            });

            $('.select2').select2();

            $('.all-check').on('ifChanged', function () {
                if ($(this).is(':checked'))
                {
                    $(this).parents('.block-form').find('.minimal').iCheck('check');
                }
                else
                {
                    $(this).parents('.block-form').find('.minimal').iCheck('uncheck');
                }
            });

            $('.minimal').on('ifChanged', function () {
                if (!$(this).is(':checked'))
                {
                    var oCheckAll = $(this).parents('.block-form').find('.all-check');
                    oCheckAll.prop('checked', false);
                    oCheckAll.iCheck('update');
                }
            });

        } );

        $('#goto-table').on('change', function(evt){
            var ref = $(this).val();

            if(ref !== ''){

                $('html, body').animate({
                    scrollTop: $('#'+ref).offset().top + 'px'
                }, 200);
            }

            location.hash = '#'+ref;
        })
    </script>
@endsection