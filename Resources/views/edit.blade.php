@extends('header')

@section('content')

    {!! Former::open($url)
            ->addClass('col-md-10 col-md-offset-1 warn-on-exit')
            ->method($method)
            ->rules(['name' => 'required','client_id' => 'required']) !!}

    @if ($access)
      {!! Former::populate($access) !!}
      <div style="display:none">
          {!! Former::text('public_id') !!}
      </div>
    @endif

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <div class="panel panel-default">
            <div class="panel-body">

                {!! Former::text('name') !!}
                {!! Former::text('host') !!}

                @if((isset($clearData) && $clearData != false) ||  !$access)
                    {!! Former::text('username') !!}
                    {!! Former::text('password')  !!}
                @endif

                {!! Former::textarea('notes') !!}
                @if ($access)
                    {!! Former::plaintext()->label('client')->value($client->present()->link) !!}
                @else
                    {!! Former::select('client_id')
                            ->label('client')
                            ->addOption('', '')
                            ->addGroupClass('client-select') !!}
                @endif


            </div>
            </div>

        </div>
    </div>

    <center class="buttons">

        {!! Button::normal(trans('texts.cancel'))
                ->large()
                ->asLinkTo(URL::to('/access'))
                ->appendIcon(Icon::create('remove-circle')) !!}

        {!! Button::success(trans('texts.save'))
                ->submit()
                ->large()
                ->appendIcon(Icon::create('floppy-disk')) !!}

    </center>

    {!! Former::close() !!}


    <script type="text/javascript">

        var clients = {!! $clients ?: 'false' !!};

        $(function() {
            $(".warn-on-exit input").first().focus();

            @if ( ! $access)
                    var $clientSelect = $('select#client_id');
                    for (var i=0; i<clients.length; i++) {
                        var client = clients[i];
                        var clientName = getClientDisplayName(client);
                        if (!clientName) {
                            continue;
                        }
                        $clientSelect.append(new Option(clientName, client.public_id));
                    }

            if ({{ $clientPublicId ? 'true' : 'false' }}) {
                $clientSelect.val({{ $clientPublicId }});
            }

            $clientSelect.combobox();

            @endif

            @if (!$clientPublicId)
            $('.client-select input.form-control').focus();
            @endif
        });

    </script>
    

@stop
